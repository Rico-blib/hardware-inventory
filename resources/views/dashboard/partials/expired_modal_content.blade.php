<table class="min-w-full text-sm text-left border border-gray-300">
    <thead class="bg-gray-100 text-xs font-semibold text-gray-700">
        <tr>
            <th class="border border-gray-300 px-3 py-2">Product</th>
            <th class="border border-gray-300 px-3 py-2">Batch No</th>
            <th class="border border-gray-300 px-3 py-2">Expiry Date</th>
            <th class="border border-gray-300 px-3 py-2">Remaining Quantity</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($expiredItems as $item)
            <tr class="hover:bg-red-50">
                <td class="border border-gray-300 px-3 py-2">{{ $item->product->name ?? 'N/A' }}</td>
                <td class="border border-gray-300 px-3 py-2">{{ $item->batch_no }}</td>
                <td class="border border-gray-300 px-3 py-2 text-red-600 font-semibold">{{ $item->expiry_date }}</td>
                <td class="border border-gray-300 px-3 py-2">{{ $item->quantity }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="border border-gray-300 px-3 py-4 text-center text-gray-500">No expired items found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
