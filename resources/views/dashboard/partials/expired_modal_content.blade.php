<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>Product</th>
            <th>Batch No</th>
            <th>Expiry Date</th>
            <th>Remaining Quantity</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($expiredItems as $item)
            <tr>
                <td>{{ $item->product->name ?? 'N/A' }}</td>
                <td>{{ $item->batch_no }}</td>
                <td class="text-danger">{{ $item->expiry_date }}</td>
                <td>{{ $item->quantity }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No expired items found.</td></tr>
        @endforelse
    </tbody>
</table>
