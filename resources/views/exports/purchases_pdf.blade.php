<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Purchases Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            margin-bottom: 0;
        }

        .date-range {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h2>Purchases Report</h2>
    <div class="date-range">
        Date Range: {{ $from ?? 'All Time' }} to {{ $to ?? 'Now' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Supplier</th>
                <th>Date</th>
                <th>Total (Ksh)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->invoice_no }}</td>
                    <td>{{ $purchase->supplier->name ?? 'Unknown' }}</td>
                    <td>{{ $purchase->created_at->format('Y-m-d') }}</td>
                    <td>{{ number_format($purchase->total, 2) }}</td>
                    <td>{{ $purchase->payment_status}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
