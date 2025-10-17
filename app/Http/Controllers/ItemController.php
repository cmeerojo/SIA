<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        $totalItems = $items->count();
        $mostRecentItem = Item::orderBy('created_at', 'desc')->first();
        $itemsWithDates = Item::orderBy('created_at', 'desc')->get();
        // recent stock movements to show in modal
        $movements = StockMovement::with('item', 'user')->orderBy('created_at', 'desc')->limit(50)->get();

        return view('items.index', compact('items', 'totalItems', 'mostRecentItem', 'itemsWithDates', 'movements'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'size' => 'required|string|max:255',
            'amount' => 'required|integer',
            'valve_type' => 'nullable|string|in:POL,A/S',
        ]);

        Item::create($request->only(['brand','size','amount','valve_type']));
        return redirect()->route('items.index')->with('success', 'Item added!');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'size' => 'required|string|max:255',
            'amount' => 'required|integer',
            'valve_type' => 'nullable|string|in:POL,A/S',
        ]);

        $item->update($request->only(['brand','size','amount','valve_type']));
        return redirect()->route('items.index')->with('success', 'Item updated!');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted!');
    }

    public function hide(Item $item)
    {
        $this->authorizeManager();
        if (!$item->is_hidden) {
            $item->update(['is_hidden' => true]);
        }

        return back()->with('success', 'Item hidden!');
    }

    public function unhide(Item $item)
    {
        $this->authorizeManager();

        if ($item->is_hidden) {
            $item->update(['is_hidden' => false]);
        }

        return back()->with('success', 'Item unhidden!');
    }

    private function authorizeManager(): void
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'manager', 403, 'Forbidden');
    }

    /**
     * Store a stock movement (add or reduce) — manager only
     */
    public function storeMovement(Request $request)
    {
        $this->authorizeManager();

        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:add,reduce',
            'reason' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = Item::findOrFail($data['item_id']);
        $previous = $item->amount;

        if ($data['type'] === 'add') {
            $new = $previous + $data['quantity'];
        } else {
            $new = $previous - $data['quantity'];
            if ($new < 0) {
                return back()->with('error', 'Quantity reduction would make stock negative.');
            }
        }

        // update item
        $item->update(['amount' => $new]);

        // record movement
        StockMovement::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'type' => $data['type'],
            'reason' => $data['reason'],
            'quantity' => $data['quantity'],
            'previous_amount' => $previous,
            'new_amount' => $new,
        ]);

        return back()->with('success', 'Stock movement recorded.');
    }
}