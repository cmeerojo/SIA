<?php

namespace App\Http\Controllers;

use App\Models\Tank;
use App\Models\TankDelivery;
use App\Models\TankMovement;
use App\Models\Customer;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TankDeliveryController extends Controller
{
    public function index()
    {
        $deliveries = TankDelivery::with(['tank', 'sale.tanks', 'customer', 'driver'])->orderBy('created_at', 'desc')->get();
        // For creating a tank delivery, offer only sales that have tanks attached and that haven't
        // already been used to create a TankDelivery (i.e., exclude sales already delivered).
        $deliveredSaleIds = TankDelivery::whereNotNull('sale_id')->pluck('sale_id')->filter()->unique()->toArray();
        $sales = \App\Models\Sale::with('customer', 'tanks')
            ->whereHas('tanks')
            ->whereNotIn('id', $deliveredSaleIds)
            ->orderByDesc('created_at')
            ->limit(200)
            ->get();
        $drivers = Driver::all();

        return view('tank_deliveries.index', compact('deliveries', 'sales', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => ['required', 'exists:sales,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'date_delivered' => ['nullable', 'date'],
        ]);

        // Server-side guard: prevent creating more than one TankDelivery for the same sale.
        if (TankDelivery::where('sale_id', $validated['sale_id'])->exists()) {
            return redirect()->back()
                ->withErrors(['sale_id' => 'A delivery has already been recorded for the selected sale.'])
                ->withInput();
        }

        // wrap in transaction: create delivery (referencing sale), update each tank status, and record movements
        DB::transaction(function () use ($validated) {
            $sale = \App\Models\Sale::with('tanks', 'customer')->findOrFail($validated['sale_id']);

            $delivery = TankDelivery::create([
                'sale_id' => $sale->id,
                // For backward compatibility keep tank_id set to the first tank in the sale if available
                'tank_id' => $sale->tanks->first()?->id ?? null,
                'customer_id' => $sale->customer_id,
                'driver_id' => $validated['driver_id'] ?? null,
                'date_delivered' => $validated['date_delivered'] ?? now(),
            ]);

            // For each tank in the sale, update status and record movement
            foreach ($sale->tanks as $tank) {
                $previous = $tank->status;
                $tank->status = 'with_customer';
                $tank->save();

                TankMovement::create([
                    'tank_id' => $tank->id,
                    'previous_status' => $previous,
                    'new_status' => 'with_customer',
                    'customer_id' => $sale->customer_id,
                    'driver_id' => $validated['driver_id'] ?? null,
                    'created_at' => now(),
                ]);
            }
        });

        return redirect()->route('tank.deliveries.index')->with('success', 'Tank delivery recorded (sale)');
    }

    /**
     * Show tank delivery map view
     */
    public function showMap(TankDelivery $tank_delivery)
    {
        $tank_delivery->load('sale.tanks', 'tank', 'customer', 'driver');
        
        // Return JSON for AJAX or view for direct access
        if (request()->wantsJson()) {
            return response()->json([
                'id' => $tank_delivery->id,
                'tank' => $tank_delivery->tank,
                'customer' => $tank_delivery->customer,
                'driver' => $tank_delivery->driver,
                'date_delivered' => $tank_delivery->date_delivered,
                'created_at' => $tank_delivery->created_at,
            ]);
        }
        
        return view('tank_deliveries.map', ['delivery' => $tank_delivery]);
    }

    /**
     * Update delivery location during delivery
     */
    public function updateLocation(Request $request, TankDelivery $tank_delivery)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Store current driver location
        $tank_delivery->update([
            'driver_latitude' => $validated['latitude'],
            'driver_longitude' => $validated['longitude'],
        ]);

        return response()->json(['success' => true]);
    }
}
