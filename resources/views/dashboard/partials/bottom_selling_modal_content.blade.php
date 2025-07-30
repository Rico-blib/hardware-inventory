<ul class="divide-y divide-red-200 text-sm">
    @forelse($bottomProducts as $product)
        <li class="flex justify-between py-2">
            <span>{{ $product->product_name }}</span>
            <span class="font-semibold text-orange-700">{{ $product->total_sold }} sold</span>
        </li>
    @empty
        <li class="text-gray-500 italic py-2">No bottom-selling data available.</li>
    @endforelse
</ul>
