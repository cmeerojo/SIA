<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Driver;
use App\Models\Customer;
use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function index()
    {
        $drivers = Driver::all();
        $customers = Customer::all();
        $items = Item::all();
        $deliveries = Delivery::with('customer', 'driver', 'item')->orderBy('created_at', 'desc')->get();

        return view('deliveries.index', compact('drivers', 'customers', 'items', 'deliveries'));
    }

    public function storeDriver(Request $request)
    {
        $this->authorizeManager();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_info' => 'nullable|string|max:255',
            'license' => 'nullable|string|max:255',
        ]);

        Driver::create($validated);

        return back()->with('success', 'Driver added.');
    }

    public function storeDelivery(Request $request)
    {
        $this->authorizeManager();

        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'dropoff_location' => 'nullable|string|max:1000',
            'driver_id' => 'required|exists:drivers,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Item::findOrFail($data['item_id']);
        if ($item->amount < $data['quantity']) {
            return back()->with('error', 'Not enough stock for this delivery.');
        }

        // reduce stock
        $previous = $item->amount;
        $item->update(['amount' => $previous - $data['quantity']]);

        // create delivery (status 'pending')
        Delivery::create([
            'customer_id' => $data['customer_id'],
            'dropoff_location' => $data['dropoff_location'] ?? Customer::find($data['customer_id'])->dropoff_location,
            'driver_id' => $data['driver_id'],
            'item_id' => $data['item_id'],
            'quantity' => $data['quantity'],
            'status' => 'pending',
        ]);

        // record stock movement as sales
        StockMovement::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'type' => 'reduce',
            'reason' => 'sales',
            'quantity' => $data['quantity'],
            'previous_amount' => $previous,
            'new_amount' => $item->amount,
        ]);

        return back()->with('success', 'Delivery scheduled.');
    }

    /**
     * Update delivery status (manager only). If cancelling, restore stock and record movement.
     */
    public function updateStatus(Request $request, Delivery $delivery)
    {
        $this->authorizeManager();

        $validated = $request->validate([
            'status' => 'required|in:pending,ongoing,completed,cancelled',
        ]);

        $newStatus = $validated['status'];
        $oldStatus = $delivery->status;

        // If moving to cancelled from a non-cancelled state, restore stock
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            $item = $delivery->item;
            if ($item) {
                $previous = $item->amount;
                $item->update(['amount' => $previous + $delivery->quantity]);

                StockMovement::create([
                    'item_id' => $item->id,
                    'user_id' => Auth::id(),
                    'type' => 'add',
                    'reason' => 'cancellation',
                    'quantity' => $delivery->quantity,
                    'previous_amount' => $previous,
                    'new_amount' => $item->amount,
                ]);
            }
        }

        // If changing from cancelled back to non-cancelled, we won't auto-reduce stock here — require manual action.

        $delivery->update(['status' => $newStatus]);

        return back()->with('success', 'Delivery status updated.');
    }

    private function authorizeManager(): void
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'manager', 403, 'Forbidden');
    }
}
