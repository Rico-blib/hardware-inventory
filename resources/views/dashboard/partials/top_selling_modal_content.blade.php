<ul class="divide-y divide-green-200 text-sm">
    @forelse($topProducts as $product)
        <li class="flex justify-between py-2">
            <span>{{ $product->product_name }}</span>
            <span class="font-semibold text-green-700">{{ $product->total_sold }} sold</span>
        </li>
    @empty
        <li class="text-gray-500 italic py-2">No top-selling data available.</li>
    @endforelse
</ul>
