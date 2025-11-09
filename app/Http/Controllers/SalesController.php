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
        $tanks = Tank::orderBy('serial_code')->get();

        // Get last 7 days data for graphs
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            $sales = Sale::whereDate('created_at', $date)->get();
            
            return [
                'date' => $date->format('M j'),
                'completed' => $sales->where('status', 'completed')->sum('price'),
                'pending' => $sales->where('status', 'pending')->sum('price'),
                'total' => $sales->sum('price'),
                'count' => $sales->count()
            ];
        });

        return view('sales.overview', compact(
            'completedAmountToday', 
            'pendingAmountToday', 
            'totalAmountToday',
            'totalOrders', 
            'pendingOrders', 
            'completedOrders',
            'last7Days',
            'customers',
            'tanks'
        ));
    }

    public function manage()
    {
        $sales = Sale::with(['customer', 'tank'])->latest()->paginate(20);
        $customers = Customer::orderBy('name')->get();
        $tanks = Tank::orderBy('serial_code')->get();

        return view('sales.manage', compact('sales', 'customers', 'tanks'));
    }

    public function store(Request $request)
    {
        $data = $request->only(['customer_id', 'tank_id', 'price', 'payment_method', 'status']);

        $validator = Validator::make($data, [
            'customer_id' => 'required|exists:customers,id',
            'tank_id' => 'required|exists:tanks,id',
            'price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,gcash,credit_card',
            'status' => 'required|in:pending,completed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Sale::create($data);

        return redirect()->route('sales.manage')
            ->with('success', 'Sale recorded successfully.');
    }

    /**
     * Display the specified sale.
     */
    public function show(Sale $sale)
    {
        return response()->json($sale->load(['customer', 'tank']));
    }

    /**
     * Update the specified sale.
     */
    public function update(Request $request, Sale $sale)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'tank_id' => 'required|exists:tanks,id',
            'price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,gcash,credit_card',
            'status' => 'required|in:pending,completed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $sale->update($request->only([
            'customer_id',
            'tank_id',
            'price',
            'payment_method',
            'status'
        ]));

        return redirect()->route('sales.manage')
            ->with('success', 'Sale updated successfully.');
    }
}
