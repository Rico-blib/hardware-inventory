<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>Product</th>
            <th>Total Quantity Left</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($lowStockItems as $item)
            <tr>
                <td>{{ $item->product->name ?? 'N/A' }}</td>
                <td>
                    {{
                        \App\Models\PurchaseItem::where('product_id', $item->product_id)
                        ->sum('quantity')
                    }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
