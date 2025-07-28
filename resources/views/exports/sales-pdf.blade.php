<!DOCTYPE html>
<html>

<head>
    <title>Sales Report</title>
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
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <h2>Sales Report</h2>
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Invoice no</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Discount</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->invoice_no }}</td>
                    <td>{{ $sale->customer->name ?? 'Walk-in' }}</td>
                    <td>{{ $sale->date }}</td>
                    <td>{{ $sale->discount }}</td>
                    <td>Ksh {{ number_format($sale->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
