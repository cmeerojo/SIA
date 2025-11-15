<html>
<head>
    <title>Purchase Order Receipt #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1 { margin: 0 0 10px; }
        table { width: 100%; border-collapse: collapse; margin-top:20px; }
        th, td { border:1px solid #ccc; padding:8px; font-size: 14px; }
        th { background:#f5f5f5; text-align:left; }
        .meta { margin-top: 10px; font-size:13px; color:#555; }
        .badge { display:inline-block; padding:4px 8px; border-radius:4px; font-size:12px; }
        .badge-pending { background:#fef9c3; color:#ca8a04; }
        .badge-received { background:#dcfce7; color:#166534; }
        @media print { button { display:none; } body { margin:0; } }
    </style>
</head>
<body>
    <button onclick="window.print()" style="float:right; padding:6px 12px;">Print</button>
    <h1>Purchase Order Receipt</h1>
    <div class="meta">PO ID: <strong>#{{ $order->id }}</strong></div>
    <div class="meta">Date: @prettyDate($order->created_at, true)</div>
    <div class="meta">Status: @if($order->status==='received')<span class="badge badge-received">Received</span>@else<span class="badge badge-pending">Pending</span>@endif</div>
    <hr />
    <h3>Supplier</h3>
    <p>{{ $order->supplier?->full_name }}<br />
        @if($order->supplier?->contact_number) Contact: {{ $order->supplier->contact_number }}<br /> @endif
        @if($order->supplier?->email) Email: {{ $order->supplier->email }}<br /> @endif
        @if($order->supplier?->contact_person) Contact Person: {{ $order->supplier->contact_person }} @endif
    </p>
    <h3>Order Details</h3>
    <table>
        <thead>
            <tr>
                <th>Brand</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ ucfirst($order->brand) }}</td>
                <td>{{ $order->size }}</td>
                <td>{{ $order->quantity }}</td>
                <td>₱{{ number_format($order->unit_price,2) }}</td>
                <td>₱{{ number_format($order->total_price,2) }}</td>
            </tr>
        </tbody>
    </table>
    @if($order->status==='received')
        <p class="meta">Received at: @prettyDate($order->received_at, true)</p>
    @endif
</body>
</html>
