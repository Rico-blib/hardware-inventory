<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Sale Details (Receipt)</h2>
    </x-slot>

    <div class="p-4" id="receipt-area">
        <h3 class="text-center mb-4 text-lg font-bold">SALES RECEIPT</h3>

        <div class="flex justify-between mb-4">
            <div>
                <p><strong>Sale ID:</strong> {{ $sale->id }}</p>
                <p><strong>Date:</strong> {{ $sale->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p><strong>Customer:</strong> {{ $sale->customer->name ?? 'Walk-in' }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1">Product</th>
                        <th class="border px-2 py-1">Qty</th>
                        <th class="border px-2 py-1">Price (Ksh)</th>
                        <th class="border px-2 py-1">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach ($sale->items as $item)
                        @php
                            $subtotal = $item->quantity * $item->price;
                            $grandTotal += $subtotal;
                        @endphp
                        <tr>
                            <td class="border px-2 py-1">{{ $item->product->name ?? 'Product Deleted' }}</td>
                            <td class="border px-2 py-1">{{ $item->quantity }}</td>
                            <td class="border px-2 py-1">{{ number_format($item->price, 2) }}</td>
                            <td class="border px-2 py-1">{{ number_format($subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-right">
            <p><strong>Subtotal:</strong> Ksh {{ number_format($grandTotal, 2) }}</p>
            <p><strong>Discount:</strong> Ksh {{ number_format($sale->discount, 2) }}</p>
            <p><strong>Total:</strong> <span class="text-lg font-bold">Ksh {{ number_format($sale->total, 2) }}</span>
            </p>
        </div>

        <div class="mt-6 text-center">
            <button  class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800"><a
                    href="{{ route('sales.receipt', $sale->id) }}" target="_blank" class="btn btn-dark">🖨 Print
                    Receipt</a>
            </button>
        </div>
    </div>
</x-app-layout>
