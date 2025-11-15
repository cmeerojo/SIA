<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Receipt #{{ $sale->id }} — {{ config('app.name') }}</title>
    <style>
        :root { --ink:#111827; --muted:#6b7280; --border:#e5e7eb; --accent:#ef4444; }
        * { box-sizing:border-box; }
        body { font-family: system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif; margin:0; padding:24px; color:var(--ink); }
        .container { max-width:760px; margin:0 auto; }
        .header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px; }
        .brand { font-size:20px; font-weight:600; }
        .sub { font-size:12px; color:var(--muted); margin-top:4px; }
        .meta { text-align:right; font-size:12px; color:var(--muted); }
        .card { border:1px solid var(--border); border-radius:12px; padding:16px; margin-bottom:16px; }
        .section-title { font-size:13px; font-weight:600; margin-bottom:8px; }
        .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:8px 16px; font-size:13px; }
        table { width:100%; border-collapse:collapse; font-size:13px; }
        th,td { padding:8px; border-bottom:1px solid var(--border); }
        th { text-align:left; font-weight:600; color:var(--muted); font-size:12px; }
        tfoot td { font-weight:600; }
        .muted { color:var(--muted); }
        .actions { display:flex; gap:8px; margin-top:16px; }
        .btn { background:#fff; border:1px solid var(--border); padding:8px 12px; font-size:13px; border-radius:8px; cursor:pointer; }
        .btn.primary { background:var(--accent); color:#fff; border-color:var(--accent); }
        @media print { .actions { display:none; } body { padding:0; } .container { max-width:100%; } }
    </style>
    <script>
        function printNow(){ window.print(); }
        function closeWin(){ window.close(); }
        window.addEventListener('load', () => { setTimeout(() => window.print(), 300); });
    </script>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <div class="brand">{{ config('app.name') }}</div>
            <div class="sub">Official Receipt</div>
        </div>
        <div class="meta">
            <div><strong>Receipt #:</strong> {{ $sale->id }}</div>
            <div><strong>Date:</strong> {{ \App\Providers\AppServiceProvider::formatPrettyDate($sale->created_at) }}</div>
        </div>
    </div>

    <div class="card">
        <div class="section-title">Customer</div>
        <div class="grid">
            <div><span class="muted">Name:</span> {{ $sale->customer?->full_name ?? $sale->customer?->name ?? 'N/A' }}</div>
            <div><span class="muted">Phone:</span> {{ $sale->customer?->phone ?? '—' }}</div>
            <div style="grid-column:1/-1"><span class="muted">Drop-off:</span> {{ $sale->customer?->dropoff_location ?: '—' }}</div>
        </div>
    </div>

    <div class="card">
        <div class="section-title">Sale Details</div>
        <div class="grid">
            <div><span class="muted">Status:</span> {{ ucfirst($sale->status) }}</div>
            <div><span class="muted">Type:</span> {{ $sale->transaction_type === 'delivery' ? 'Delivery' : 'Walk-in' }}</div>
            <div><span class="muted">Payment:</span> {{ ucfirst(str_replace('_',' ', $sale->payment_method)) }}</div>
            <div><span class="muted">Quantity:</span> {{ $sale->quantity ?? 1 }}</div>
        </div>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Tank Serial</th>
                    <th>Brand</th>
                    <th>Size</th>
                </tr>
            </thead>
            <tbody>
            @php($tanks = $sale->tanks && $sale->tanks->count() ? $sale->tanks : collect([$sale->tank]->filter()))
            @forelse($tanks as $t)
                <tr>
                    <td>{{ $t->serial_code ?? '—' }}</td>
                    <td>{{ $t->brand ?? '—' }}</td>
                    <td>{{ $t->size ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="muted">No tank details.</td></tr>
            @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align:right">Total</td>
                    <td>₱{{ number_format((float)($sale->price ?? 0), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="actions">
        <button class="btn primary" onclick="printNow()">Print</button>
        <button class="btn" onclick="closeWin()">Close</button>
    </div>

    <div class="muted" style="margin-top:12px;font-size:11px">Thank you for your business.</div>
</div>
</body>
</html>