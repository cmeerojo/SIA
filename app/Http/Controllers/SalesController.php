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
                'label' => $d->format('M Y'),
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
