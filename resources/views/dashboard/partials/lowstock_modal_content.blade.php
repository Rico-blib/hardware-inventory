<table class="min-w-full text-sm text-left border border-gray-300">
    <thead class="bg-gray-100 text-xs font-semibold text-gray-700">
        <tr>
            <th class="border border-gray-300 px-3 py-2">Product</th>
            <th class="border border-gray-300 px-3 py-2">Total Quantity Left</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($lowStockItems as $item)
            <tr class="hover:bg-yellow-50">
                <td class="border border-gray-300 px-3 py-2">{{ $item->product->name ?? 'N/A' }}</td>
                <td class="border border-gray-300 px-3 py-2">
                    {{ \App\Models\PurchaseItem::where('product_id', $item->product_id)->sum('quantity') }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="border border-gray-300 px-3 py-4 text-center text-gray-500">
                    No low stock products at the moment.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
