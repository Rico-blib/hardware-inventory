<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Sales List</h2>
    </x-slot>

    <div class="py-4 px-6">
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Controls -->
        <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
            <div class="space-x-2">
                <a href="{{ route('sales.create') }}"
                    class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded shadow">
                    + Add Sale
                </a>
                <a href="#" class="bg-orange-700 hover:bg-orange-800 text-white px-4 py-2 rounded">
                    Export to Excel
                </a>
            </div>

            <div class="flex items-center gap-4 flex-wrap">
                <!-- Search -->
                <form method="GET" action="{{ route('sales.index') }}">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search invoice/customer"
                        class="border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300" />
                </form>

                <!-- Show entries -->
                <form method="GET" action="{{ route('sales.index') }}">
                    <select name="per_page" onchange="this.form.submit()" class="border rounded px-3 py-2">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}"
                                {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                Show {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Id</th>
                        <th class="px-4 py-2 text-left">Invoice</th>
                        <th class="px-4 py-2 text-left">Customer</th>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Total</th>
                        <th class="px-4 py-2 text-left">Discount</th>
                        <th class="px-4 py-2 text-left">Grand Total</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody x-data="{ editing: null }">
                    @forelse ($sales as $sale)
                        <tr class="border-t hover:bg-gray-50" x-show="editing !== {{ $sale->id }}">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $sale->invoice_no }}</td>
                            <td class="px-4 py-2">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                            <td class="px-4 py-2">{{ $sale->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-2">Ksh {{ number_format($sale->total, 2) }}</td>
                            <td class="px-4 py-2">Ksh {{ number_format($sale->discount, 2) }}</td>
                            <td class="px-4 py-2 font-bold">Ksh {{ number_format($sale->grand_total, 2) }}</td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('sales.show', $sale->id) }}"
                                        class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded">
                                        <x-heroicon-o-eye class="w-5 h-5" />
                                    </a>

                                    <button @click="editing = {{ $sale->id }}"
                                        class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded">
                                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                                    </button>

                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this sale?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Inline Edit Row -->
                        <tr class="bg-gray-100" x-show="editing === {{ $sale->id }}">
                            <td colspan="8" class="px-4 py-4">
                                <form action="{{ route('sales.update', $sale->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="flex gap-4 items-center flex-wrap">
                                        <select name="customer_id" class="border rounded px-3 py-2">
                                            <option value="">Walk-in</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <input type="number" name="discount" step="0.01"
                                            value="{{ $sale->discount }}" class="border rounded px-3 py-2"
                                            oninput="
                                                    const total = {{ $sale->items->sum(fn($i) => $i->price * $i->quantity) }};
                                                    const discount = parseFloat(this.value) || 0;
                                                    this.closest('form').querySelector('.grand-total').textContent = 'Ksh ' + (total - discount).toFixed(2);
                                               " />

                                        <span class="font-semibold text-green-700">
                                            Grand Total:
                                            <span class="grand-total">
                                                Ksh {{ number_format($sale->grand_total, 2) }}
                                            </span>
                                        </span>

                                        <div class="flex gap-2 mt-2">
                                            <button type="submit"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                                Update
                                            </button>
                                            <button type="button" @click="editing = null"
                                                class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">No sales found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $sales->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
