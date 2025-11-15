<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Tank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    protected function ensureManager(): void
    {
        if (!auth()->check() || !auth()->user()->isManager()) {
            abort(403, 'Managers only.');
        }
    }

    public function index()
    {
        $this->ensureManager();
        $orders = PurchaseOrder::with('supplier')->latest()->paginate(25);
        $suppliers = Supplier::orderBy('first_name')->get();
        $brandPrices50 = config('tank_prices.50kg', []);
        return view('purchase_orders.index', compact('orders', 'suppliers', 'brandPrices50'));
    }

    public function store(Request $request)
    {
        $this->ensureManager();

        $data = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_first_name' => 'nullable|string',
            'supplier_last_name' => 'nullable|string',
            'supplier_contact_number' => 'nullable|string',
            'supplier_email' => 'nullable|email',
            'supplier_contact_person' => 'nullable|string',
            'brand' => 'required|string',
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        // Supplier creation if not provided
        $supplierId = $data['supplier_id'] ?? null;
        if (!$supplierId) {
            $supplier = Supplier::create([
                'first_name' => $data['supplier_first_name'] ?? 'N/A',
                'last_name' => $data['supplier_last_name'] ?? 'N/A',
                'contact_number' => $data['supplier_contact_number'] ?? null,
                'email' => $data['supplier_email'] ?? null,
                'contact_person' => $data['supplier_contact_person'] ?? null,
            ]);
            $supplierId = $supplier->id;
        }

        $brandKey = strtolower(trim($data['brand']));
        $sizeKey = strtolower(trim($data['size']));
        $priceMap50 = config('tank_prices.50kg', []);
        $is50 = in_array($sizeKey, ['50kg','50 kg','50']) || str_contains($sizeKey,'50');
        $unitPrice = ($is50 && isset($priceMap50[$brandKey])) ? (float)$priceMap50[$brandKey] : (float)($data['unit_price'] ?? 0);
        $total = $unitPrice * (int)$data['quantity'];

        $order = PurchaseOrder::create([
            'supplier_id' => $supplierId,
            'brand' => $data['brand'],
            'size' => $data['size'],
            'quantity' => $data['quantity'],
            'unit_price' => $unitPrice,
            'total_price' => $total,
            'status' => 'pending',
        ]);

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order created.');
    }

    public function receipt(PurchaseOrder $purchase_order)
    {
        $this->ensureManager();
        $purchase_order->load('supplier');
        return view('purchase_orders.receipt', ['order' => $purchase_order]);
    }

    public function markReceived(PurchaseOrder $purchase_order)
    {
        $this->ensureManager();
        if ($purchase_order->status === 'received') {
            return redirect()->back()->with('info', 'Already marked received.');
        }

        DB::transaction(function () use ($purchase_order) {
            $purchase_order->status = 'received';
            $purchase_order->received_at = now();
            $purchase_order->save();

            // Generate unmarked tanks records (each counts as one unit)
            for ($i = 0; $i < $purchase_order->quantity; $i++) {
                Tank::create([
                    'serial_code' => 'PO'.$purchase_order->id.'-'.str_pad($i+1, 3, '0', STR_PAD_LEFT),
                    'status' => 'filled',
                    'brand' => $purchase_order->brand,
                    'valve_type' => null,
                    'size' => $purchase_order->size,
                    'amount' => 1, // ensure appears in aggregated amount
                    'is_hidden' => false,
                    'is_unmarked' => true,
                ]);
            }
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Order marked received and tanks added.');
    }
}
