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
    {{-- Logo and App Name --}}
    @if ($settings?->logo)
        <div class="text-center">
            <img src="{{ asset('storage/logos/' . $settings->logo) }}" alt="Logo" style="max-height: 80px;">
        </div>
    @endif

    <h2 class="text-center">{{ $settings->app_name ?? 'SALES RECEIPT' }}</h2>

    {{-- Contact Details --}}
    @if ($settings?->contact_number || $settings?->email)
        <p class="text-center">
            @if ($settings->contact_number)
                📞 {{ $settings->contact_number }}<br>
            @endif
            @if ($settings->email)
                ✉️ {{ $settings->email }}
            @endif
        </p>
    @endif
    <hr style="border: none; border-top: 1px dashed #000; margin: 10px 0;">

    {{-- Sale Information --}}
    <p>
        <strong>Sale ID:</strong> {{ $sale->id }}<br>
        <strong>Date:</strong> {{ $sale->created_at->format('d M Y, H:i') }}<br>
        <strong>Customer:</strong> {{ $sale->customer->name ?? 'Walk-in' }}<br>
        <strong>Served By:</strong> {{ $sale->user->name ?? 'Unknown' }} <br>
        <strong>Payment Method:</strong> {{ ucfirst($sale->payment_method) }}

    </p>

    {{-- Items Table --}}
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

    {{-- Totals --}}
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
