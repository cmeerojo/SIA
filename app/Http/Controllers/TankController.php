<?php

namespace App\Http\Controllers;

use App\Models\Tank;
use App\Models\TankMovement;
use Illuminate\Http\Request;

class TankController extends Controller
{
    /**
     * Serve the Items index but backed by Tanks (full replacement of Items)
     */
    public function index(Request $request)
    {
        // Treat Tanks as Items for the items UI
        $items = Tank::orderBy('created_at', 'desc')->get();
        $totalItems = $items->count();
        $mostRecentItem = $items->first();
        $itemsWithDates = $items; // already ordered by created_at desc

        // Tanks quick access - apply search filter if provided
        $tankQuery = Tank::orderBy('created_at', 'desc');
        
        if ($request->has('tank_search') && !empty($request->tank_search)) {
            $search = $request->tank_search;
            $tankQuery->where(function($q) use ($search) {
                $q->where('serial_code', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('valve_type', 'like', "%{$search}%");
            });
        }
        
        $recentTanks = $tankQuery->limit(100)->get(); // Increased limit to show more results when searching
        $tanksInStore = Tank::where('status', 'filled')->count();
        $tanksWithCustomers = Tank::where('status', 'with_customer')->count();

        // Group tanks by brand/size/valve_type for the items table (aggregated view)
        $groups = Tank::select('brand', 'size', 'valve_type')
            ->whereNotNull('brand')
            ->groupBy('brand', 'size', 'valve_type')
            ->selectRaw('brand, size, valve_type, SUM(COALESCE(amount,0)) as total_amount, COUNT(serial_code) as serial_count')
            ->get();

        // Map serials per group key for dropdown display
        $serials = Tank::whereNotNull('serial_code')
            ->get()
            ->groupBy(function ($t) {
                return ($t->brand ?? '') . '||' . ($t->size ?? '') . '||' . ($t->valve_type ?? '');
            });

        return view('items.index', compact(
            'items', 'totalItems', 'mostRecentItem', 'itemsWithDates',
            'recentTanks', 'tanksInStore', 'tanksWithCustomers', 'groups', 'serials'
        ))->with('tankSearch', $request->tank_search ?? '');
    }

    public function create()
    {
        // reuse items.create if present, otherwise fall back to tanks.create
        if (view()->exists('items.create')) {
            return view('items.create');
        }
        return view('tanks.create');
    }

    public function store(Request $request)
    {
        // Accept two flavors:
        // 1) Item-style creation (brand,size,amount,valve_type) — coming from items UI
        // 2) Tank-style creation (serial_code,status,brand,valve_type)

        if ($request->has('serial_code')) {
            $validated = $request->validate([
                'serial_code' => ['required', 'string', 'max:255', 'unique:tanks,serial_code'],
                'status' => ['required', 'in:filled,empty,with_customer'],
                'brand' => ['nullable', 'string', 'max:255'],
                'valve_type' => ['nullable', 'in:POL,A/S'],
                'size' => ['required', 'string', 'max:255'],
            ]);

            $tank = Tank::create([
                'serial_code' => $validated['serial_code'],
                'status' => $validated['status'],
                'brand' => $validated['brand'] ?? null,
                'valve_type' => $validated['valve_type'] ?? null,
                'size' => $validated['size'],
                'amount' => 1, // individual serial-coded tank represents one unit
            ]);

            TankMovement::create([
                'tank_id' => $tank->id,
                'previous_status' => null,
                'new_status' => $tank->status,
                'customer_id' => null,
                'driver_id' => null,
                'created_at' => now(),
            ]);

            if ($request->has('from_items')) {
                return redirect()->route('items.index')->with('success', 'Tank created');
            }

            return redirect()->route('tanks.index')->with('success', 'Tank created');
        }

        // Item-style
        $request->validate([
            'brand' => 'required|string|max:255',
            'size' => 'required|string|max:255',
            'amount' => 'required|integer',
            'valve_type' => 'nullable|string|in:POL,A/S',
        ]);

        $tank = Tank::create([
            'brand' => $request->input('brand'),
            'size' => $request->input('size'),
            'amount' => (int) $request->input('amount'),
            'valve_type' => $request->input('valve_type'),
            // default status based on amount
            'status' => $request->input('amount') > 0 ? 'filled' : 'empty',
        ]);

        return redirect()->route('items.index')->with('success', 'Item created (backed by Tank)');
    }

    public function show(Tank $tank)
    {
        $movements = $tank->movements()->orderBy('created_at', 'desc')->get();

        // If showing from items context, render tanks history view
        if (view()->exists('tanks.show')) {
            return view('tanks.show', compact('tank', 'movements'));
        }

        return view('items.show', compact('tank', 'movements'));
    }

    public function edit(Tank $tank)
    {
        // Reuse items.edit so existing UI remains the same
        if (view()->exists('items.edit')) {
            return view('items.edit', ['item' => $tank]);
        }
        return view('tanks.edit', compact('tank'));
    }

    public function update(Request $request, Tank $tank)
    {
        // Support both item-style update and tank-style update
        if ($request->has('serial_code')) {
            $validated = $request->validate([
                'serial_code' => ['required', 'string', 'max:255', "unique:tanks,serial_code,{$tank->id}"],
                'status' => ['required', 'in:filled,empty,with_customer'],
                'brand' => ['nullable', 'string', 'max:255'],
                'valve_type' => ['nullable', 'in:POL,A/S'],
            ]);

            $previous = $tank->status;
            $tank->update($validated);

            if ($previous !== $tank->status) {
                TankMovement::create([
                    'tank_id' => $tank->id,
                    'previous_status' => $previous,
                    'new_status' => $tank->status,
                    'customer_id' => null,
                    'driver_id' => null,
                    'created_at' => now(),
                ]);
            }

            if ($request->has('from_items')) {
                return redirect()->route('items.index')->with('success', 'Tank updated');
            }

            return redirect()->route('tanks.index')->with('success', 'Tank updated');
        }

        // Item-style update
        $request->validate([
            'brand' => 'required|string|max:255',
            'size' => 'required|string|max:255',
            'amount' => 'required|integer',
            'valve_type' => 'nullable|string|in:POL,A/S',
        ]);

        $tank->update([
            'brand' => $request->input('brand'),
            'size' => $request->input('size'),
            'amount' => (int) $request->input('amount'),
            'valve_type' => $request->input('valve_type'),
        ]);

        return redirect()->route('items.index')->with('success', 'Item (Tank) updated');
    }

    public function destroy(Tank $tank)
    {
        $tank->delete();

        // If this was used from the items UI, send back there
        return redirect()->route('items.index')->with('success', 'Item (Tank) deleted');
    }

    /**
     * Hide a tank (manager only) — keeps parity with old ItemController::hide
     */
    public function hide(Tank $tank)
    {
        $this->authorizeManager();
        if (!$tank->is_hidden) {
            $tank->update(['is_hidden' => true]);
        }

        return back()->with('success', 'Item hidden!');
    }

    public function unhide(Tank $tank)
    {
        $this->authorizeManager();

        if ($tank->is_hidden) {
            $tank->update(['is_hidden' => false]);
        }

        return back()->with('success', 'Item unhidden!');
    }

    private function authorizeManager(): void
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'manager', 403, 'Forbidden');
    }

    /**
     * Placeholder for stock movements route. The old StockMovement model is tightly coupled
     * to the old `items` table. For now this endpoint will operate on Tanks and adjust the
     * `amount` field; it will *not* create StockMovement rows to avoid FK issues.
     */
    public function storeMovement(Request $request)
    {
        $this->authorizeManager();

        $data = $request->validate([
            'item_id' => 'required|exists:tanks,id',
            'type' => 'required|in:add,reduce',
            'reason' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $tank = Tank::findOrFail($data['item_id']);
        $previous = $tank->amount ?? 0;

        if ($data['type'] === 'add') {
            $new = $previous + $data['quantity'];
        } else {
            $new = $previous - $data['quantity'];
            if ($new < 0) {
                return back()->with('error', 'Quantity reduction would make stock negative.');
            }
        }

        $tank->update(['amount' => $new]);

        // NOTE: not creating StockMovement row because that model/table expects items.

        return back()->with('success', 'Stock movement recorded on Tank (amount updated).');
    }
}
