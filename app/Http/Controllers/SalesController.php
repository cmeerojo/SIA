<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Tank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function overview()
    {
        $today = Carbon::today();

        // Get today's sales amounts
        $salesToday = Sale::whereDate('created_at', $today)->get();
        $completedAmountToday = $salesToday->where('status', 'completed')->sum('price');
        $pendingAmountToday = $salesToday->where('status', 'pending')->sum('price');
        $totalAmountToday = $completedAmountToday + $pendingAmountToday;

        // Get orders count
        $totalOrders = Sale::count();
        $pendingOrders = Sale::where('status', 'pending')->count();
        $completedOrders = Sale::where('status', 'completed')->count();

        // Get customers for the add sale modal
        $customers = Customer::orderBy('name')->get();
        // Get only available tanks (filled status) for new sales
        $tanks = Tank::where('status', 'filled')->orderBy('serial_code')->get();

        // Get last 7 days data for graphs
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            $sales = Sale::whereDate('created_at', $date)->get();
            
            return [
                'date' => $date->format('F j'),
                'completed' => $sales->where('status', 'completed')->sum('price'),
                'pending' => $sales->where('status', 'pending')->sum('price'),
                'total' => $sales->sum('price'),
                'count' => $sales->count()
            ];
        });

        // Monthly aggregates for last 12 months (including current month)
        $startOfWindow = Carbon::now()->startOfMonth()->subMonths(11);
        $rawMonthly = Sale::selectRaw("
                DATE_FORMAT(created_at, '%Y-%m') as ym,
                DATE_FORMAT(created_at, '%b %Y') as label,
                SUM(price) as total,
                SUM(CASE WHEN status = 'completed' THEN price ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'pending' THEN price ELSE 0 END) as pending,
                COUNT(*) as count
            ")
            ->where('created_at', '>=', $startOfWindow)
            ->groupBy('ym', 'label')
            ->orderBy('ym')
            ->get();

        // Ensure we have all months in window, even with zero values
        $monthsRange = collect(range(0, 11))->map(function ($i) use ($startOfWindow) {
            $d = (clone $startOfWindow)->addMonths($i);
            return [
                'ym' => $d->format('Y-m'),
                    'label' => $d->format('F Y'),
            ];
        });
        $monthlyByYm = $rawMonthly->keyBy('ym');
        $last12Months = $monthsRange->map(function ($m) use ($monthlyByYm) {
            $row = $monthlyByYm->get($m['ym']);
            return [
                'ym' => $m['ym'],
                'label' => $m['label'],
                'total' => $row?->total ? (float)$row->total : 0,
                'completed' => $row?->completed ? (float)$row->completed : 0,
                'pending' => $row?->pending ? (float)$row->pending : 0,
                'count' => $row?->count ? (int)$row->count : 0,
            ];
        });

        // Sales breakdown by payment method (last 30 days)
        $paymentWindow = Carbon::now()->subDays(30);
        $paymentsRaw = Sale::selectRaw("payment_method, SUM(price) as total, COUNT(*) as count")
            ->where('created_at', '>=', $paymentWindow)
            ->groupBy('payment_method')
            ->get();
        $salesByPaymentMethod = $paymentsRaw->map(function ($row) {
            return [
                'method' => $row->payment_method ?? 'unknown',
                'total' => (float)$row->total,
                'count' => (int)$row->count,
            ];
        });

        // Top customers by amount in last 30 days
        $topCustomers = Sale::selectRaw("customer_id, SUM(price) as total, COUNT(*) as count")
            ->where('created_at', '>=', $paymentWindow)
            ->groupBy('customer_id')
            ->orderByDesc('total')
            ->with('customer')
            ->limit(10)
            ->get();

        return view('sales.overview', compact(
            'completedAmountToday', 
            'pendingAmountToday', 
            'totalAmountToday',
            'totalOrders', 
            'pendingOrders', 
            'completedOrders',
            'last7Days',
            'last12Months',
            'salesByPaymentMethod',
            'topCustomers',
            'customers',
            'tanks'
        ));
    }

    public function manage()
    {
        $sales = Sale::with(['customer', 'tank', 'tanks'])->latest()->paginate(20);
        $customers = Customer::orderBy('name')->get();
        // Get available tanks (filled status) and tanks already in sales (for editing)
        $tankIdsInSales = Sale::with('tanks')->get()->pluck('tanks')->flatten()->pluck('id')->unique();
        // Tanks available for creating a new sale: only those currently filled (exclude with_customer)
        $availableTanks = Tank::where('status', 'filled')->orderBy('serial_code')->get();

        // Tanks to show in the manage table / edit modal: include filled tanks and tanks already attached to sales
        $tanks = Tank::where(function($q) use ($tankIdsInSales) {
            $q->where('status', 'filled')
              ->orWhereIn('id', $tankIdsInSales); // Include tanks already in sales for editing
        })->orderBy('serial_code')->get();

        return view('sales.manage', compact('sales', 'customers', 'tanks', 'availableTanks'));
    }

    public function store(Request $request)
    {
        // Normalize payment method to canonical values
        $method = strtolower(trim((string)($request->payment_method ?? '')));
        $method = str_replace([' ', '-', '_'], '', $method);
        $map = [
            'cash' => 'cash',
            'gcash' => 'gcash',
            'gcashpay' => 'gcash',
            'g' => 'gcash',
            'gpay' => 'gcash',
            'creditcard' => 'credit_card',
            'card' => 'credit_card',
        ];
        $paymentMethod = $map[$method] ?? $request->payment_method;

        // Get quantity and tank IDs
        $quantity = (int)($request->quantity ?? 1);
        $tankIds = $request->tank_ids ?? [];
        
        // If single tank_id is provided (backward compatibility), use it
        if ($request->tank_id && empty($tankIds)) {
            $tankIds = [$request->tank_id];
            $quantity = 1;
        }

        // Validate
        $validator = Validator::make([
            'customer_id' => $request->customer_id,
            'tank_ids' => $tankIds,
            'quantity' => $quantity,
            'price' => $request->price,
            'payment_method' => $paymentMethod,
            'status' => $request->status,
            'transaction_type' => $request->transaction_type,
        ], [
            'customer_id' => 'required|exists:customers,id',
            'tank_ids' => 'required|array|min:1',
            'tank_ids.*' => 'exists:tanks,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,gcash,credit_card',
            'status' => 'required|in:pending,completed',
            'transaction_type' => 'required|in:walk_in,delivery',
        ]);

        // Validate quantity matches number of tanks
        if (count($tankIds) !== $quantity) {
            $validator->errors()->add('quantity', 'Quantity must match the number of selected tanks.');
        }

        // Validate tanks are available
        $availableTanks = Tank::whereIn('id', $tankIds)
            ->where('status', 'filled')
            ->count();
        
        if ($availableTanks !== count($tankIds)) {
            $validator->errors()->add('tank_ids', 'Some selected tanks are not available (must be filled status).');
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create sale
        $sale = Sale::create([
            'customer_id' => $request->customer_id,
            'tank_id' => $tankIds[0] ?? null, // Keep for backward compatibility
            'quantity' => $quantity,
            'price' => $request->price,
            'payment_method' => $paymentMethod,
            'status' => $request->status,
            'transaction_type' => $request->transaction_type ?? 'walk_in',
        ]);

        // Attach tanks to sale
        $sale->tanks()->attach($tankIds);

        // Update tank status if sale is completed
        if ($request->status === 'completed' && ($request->transaction_type ?? 'walk_in') === 'walk_in') {
            Tank::whereIn('id', $tankIds)->update(['status' => 'with_customer']);
        }

        return redirect()->route('sales.manage')
            ->with('success', 'Sale recorded successfully.');
    }

    /**
     * Display the specified sale.
     */
    public function show(Sale $sale)
    {
        return response()->json($sale->load(['customer', 'tank', 'tanks']));
    }

    /**
     * Update the specified sale.
     */
    public function update(Request $request, Sale $sale)
    {
        // Normalize payment method to canonical values
        $method = strtolower(trim((string)($request->payment_method ?? '')));
        $method = str_replace([' ', '-', '_'], '', $method);
        $map = [
            'cash' => 'cash',
            'gcash' => 'gcash',
            'gcashpay' => 'gcash',
            'g' => 'gcash',
            'gpay' => 'gcash',
            'creditcard' => 'credit_card',
            'card' => 'credit_card',
        ];
        $paymentMethod = $map[$method] ?? $request->payment_method;

        // Get quantity and tank IDs
        $quantity = (int)($request->quantity ?? 1);
        $tankIds = $request->tank_ids ?? [];
        
        // If single tank_id is provided (backward compatibility), use it
        if ($request->tank_id && empty($tankIds)) {
            $tankIds = [$request->tank_id];
            $quantity = 1;
        }

        // Get old tank IDs to restore their status if needed
        $oldTankIds = $sale->tanks->pluck('id')->toArray();
        $oldStatus = $sale->status;

        // Validate
        $validator = Validator::make([
            'customer_id' => $request->customer_id,
            'tank_ids' => $tankIds,
            'quantity' => $quantity,
            'price' => $request->price,
            'payment_method' => $paymentMethod,
            'status' => $request->status,
            'transaction_type' => $request->transaction_type,
        ], [
            'customer_id' => 'required|exists:customers,id',
            'tank_ids' => 'required|array|min:1',
            'tank_ids.*' => 'exists:tanks,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,gcash,credit_card',
            'status' => 'required|in:pending,completed',
            'transaction_type' => 'required|in:walk_in,delivery',
        ]);

        // Validate quantity matches number of tanks
        if (count($tankIds) !== $quantity) {
            $validator->errors()->add('quantity', 'Quantity must match the number of selected tanks.');
        }

        // Validate tanks are available (if status changed to completed or tanks changed)
        if ($request->status === 'completed' || $tankIds !== $oldTankIds || $sale->transaction_type !== ($request->transaction_type ?? 'walk_in')) {
            $availableTanks = Tank::whereIn('id', $tankIds)
                ->where(function($q) use ($oldTankIds) {
                    $q->where('status', 'filled')
                      ->orWhereIn('id', $oldTankIds); // Allow tanks already in this sale
                })
                ->count();
            
            if ($availableTanks !== count($tankIds)) {
                $validator->errors()->add('tank_ids', 'Some selected tanks are not available.');
            }
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update sale
        $sale->update([
            'customer_id' => $request->customer_id,
            'tank_id' => $tankIds[0] ?? null, // Keep for backward compatibility
            'quantity' => $quantity,
            'price' => $request->price,
            'payment_method' => $paymentMethod,
            'status' => $request->status,
            'transaction_type' => $request->transaction_type ?? $sale->transaction_type ?? 'walk_in',
        ]);

        // Update tanks relationship
        $sale->tanks()->sync($tankIds);

        // Handle tank status changes
        $oldType = $sale->getOriginal('transaction_type') ?? 'walk_in';
        $newType = $request->transaction_type ?? $oldType;

        if ($oldStatus === 'completed' && $request->status !== 'completed') {
            // Revert old tanks to filled if sale is no longer completed
            if ($oldType === 'walk_in') {
                Tank::whereIn('id', $oldTankIds)->update(['status' => 'filled']);
            }
        } elseif ($oldStatus !== 'completed' && $request->status === 'completed') {
            // Only mark as with_customer for walk-in
            if ($newType === 'walk_in') {
                Tank::whereIn('id', $tankIds)->update(['status' => 'with_customer']);
            }
            // Revert old tanks if they changed
            if ($oldTankIds !== $tankIds) {
                $removedTankIds = array_diff($oldTankIds, $tankIds);
                if (!empty($removedTankIds)) {
                    if ($oldType === 'walk_in') {
                        Tank::whereIn('id', $removedTankIds)->update(['status' => 'filled']);
                    }
                }
            }
        } elseif ($oldStatus === 'completed' && $request->status === 'completed' && $oldTankIds !== $tankIds) {
            // Tanks changed but still completed
            $removedTankIds = array_diff($oldTankIds, $tankIds);
            $newTankIds = array_diff($tankIds, $oldTankIds);
            if (!empty($removedTankIds) && $oldType === 'walk_in') {
                Tank::whereIn('id', $removedTankIds)->update(['status' => 'filled']);
            }
            if (!empty($newTankIds) && $newType === 'walk_in') {
                Tank::whereIn('id', $newTankIds)->update(['status' => 'with_customer']);
            }
        }

        return redirect()->route('sales.manage')
            ->with('success', 'Sale updated successfully.');
    }

    /**
     * Show printable receipt for a sale.
     */
    public function receipt(Sale $sale)
    {
        $sale->load(['customer', 'tanks', 'tank']);
        return view('sales.receipt', compact('sale'));
    }
}
