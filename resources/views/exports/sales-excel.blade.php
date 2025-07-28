<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Invoice_no</th>
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
                <td>{{ $sale->total }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
