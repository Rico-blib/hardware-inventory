<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Purchase Details - Invoice #{{ $purchase->invoice_no }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow space-y-6">
        <!-- Flash Message -->
        @if (session('success'))
            <div class="px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Purchase Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
            <div><strong>Invoice No:</strong> {{ $purchase->invoice_no }}</div>
            <div><strong>Date:</strong> {{ $purchase->date }}</div>
            <div><strong>Supplier:</strong> {{ $purchase->supplier->name }}</div>
            <div><strong>Payment Status:</strong>
                <span class="capitalize">{{ $purchase->payment_status }}</span>
            </div>
            <div><strong>Total:</strong> Ksh {{ number_format($purchase->total, 2) }}</div>
        </div>

        <!-- Purchase Items Table -->
        <div class="overflow-x-auto">
            <h3 class="text-lg font-semibold mt-4 mb-2">Purchased Items</h3>
            <table class="w-full border bg-white shadow rounded">
                <thead class="bg-gray-800 text-white text-sm">
                    <tr>
                        <th class="px-4 py-2">Id</th>
                        <th class="px-4 py-2">Product</th>
                        <th class="px-4 py-2">Qty</th>
                        <th class="px-4 py-2">Cost Price</th>
                        <th class="px-4 py-2">Selling Price</th>
                        <th class="px-4 py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase->items as $item)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $item->product->name }}</td>
                            <td class="px-4 py-2">{{ $item->quantity }}</td>
                            <td class="px-4 py-2">Ksh {{ number_format($item->cost_price, 2) }}</td>
                            <td class="px-4 py-2">Ksh {{ number_format($item->selling_price, 2) }}</td>
                            <td class="px-4 py-2">Ksh {{ number_format($item->quantity * $item->cost_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Back Button -->
        <div>
            <a href="{{ route('purchases.index') }}"
                class="inline-block bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900">
                ← Back to Purchases
            </a>
        </div>
    </div>
</x-app-layout>
