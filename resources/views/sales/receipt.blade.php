<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sales Receipt</title>
    <style>
        body {
            font-family: monospace;
            font-size: 14px;
            margin: 10px;
        }

        .text-center {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border-bottom: 1px dashed #000;
            padding: 4px;
            text-align: left;
        }

        .totals {
            margin-top: 10px;
            text-align: right;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <h2 class="text-center">SALES RECEIPT</h2>

    <p>
        <strong>Sale ID:</strong> {{ $sale->id }}<br>
        <strong>Date:</strong> {{ $sale->created_at->format('d M Y, H:i') }}<br>
        <strong>Customer:</strong> {{ $sale->customer->name ?? 'Walk-in' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($sale->items as $item)
                @php
                    $subtotal = $item->quantity * $item->price;
                    $grandTotal += $subtotal;
                @endphp
                <tr>
                    <td>{{ $item->product->name ?? 'Deleted' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p><strong>Subtotal:</strong> Ksh {{ number_format($grandTotal, 2) }}</p>
        <p><strong>Discount:</strong> Ksh {{ number_format($sale->discount, 2) }}</p>
        <p><strong>Total:</strong> Ksh {{ number_format($sale->total, 2) }}</p>
    </div>

    <div class="text-center">
        <button onclick="window.print()">🖨 Print</button>
    </div>
</body>

</html>
