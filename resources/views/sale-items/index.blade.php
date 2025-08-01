<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Sales Items</h2>
    </x-slot>

    <div class="py-4 px-6">
        <!-- Flash Message -->
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Controls -->
        <div class="flex justify-between items-center mb-4">
            <a href="{{ route('sales.index') }}" class="bg-blue-800 hover:bg-blue-900 text-white px-4 py-2 rounded">
                + Add Sale Item
            </a>

            <div class="flex items-center space-x-4">
                <!-- Entries per page -->
                <form method="GET" action="{{ route('sale-items.index') }}">
                    <select name="per_page" onchange="this.form.submit()" class="border rounded px-2 py-1">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ ($perPage ?? 10) == $size ? 'selected' : '' }}>
                                Show {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Search -->
                <form method="GET" action="{{ route('sale-items.index') }}">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Search product/customer..." class="border px-2 py-1 rounded">
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full bg-white shadow rounded">
                <thead class="bg-gray-800 text-white text-left">
                    <tr>
                        <th class="px-4 py-2">Id</th>
                        <th class="px-4 py-2">Product</th>
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Subtotal</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr class="border-t" x-data="{
                            editMode: false,
                            quantity: '{{ $item->quantity }}',
                            price: '{{ $item->price }}',
                            submitForm() {
                                this.$refs.form.submit();
                                this.editMode = false;
                            }
                        }">
                            <td class="px-4 py-2">{{ $item->id }}</td>
                            <td class="px-4 py-2">{{ $item->product->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $item->sale->customer->name ?? 'Walk-in' }}</td>

                            <!-- Quantity -->
                            <td class="px-4 py-2">
                                <template x-if="editMode">
                                    <input type="number" x-model="quantity" class="border px-2 py-1 w-full" />
                                </template>
                                <template x-if="!editMode">
                                    <span x-text="quantity"></span>
                                </template>
                            </td>

                            <!-- Price -->
                            <td class="px-4 py-2">
                                <template x-if="editMode">
                                    <input type="number" step="0.01" x-model="price"
                                        class="border px-2 py-1 w-full" />
                                </template>
                                <template x-if="!editMode">
                                    <span x-text="price"></span>
                                </template>
                            </td>

                            <!-- Subtotal -->
                            <td class="px-4 py-2">
                                Ksh <span x-text="(quantity * price).toFixed(2)"></span>
                            </td>

                            <!-- Date -->
                            <td class="px-4 py-2">
                                {{ $item->sale->created_at->format('d M Y H:i') }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-2 flex space-x-2">
                                <!-- Edit / Save Button -->
                                <button @click="editMode ? submitForm() : editMode = true" type="button"
                                    class="text-blue-600 hover:text-blue-800">
                                    <span x-text="editMode ? '💾' : '✏️'"></span>
                                </button>

                                <!-- Hidden Update Form -->
                                <form x-ref="form" method="POST" action="{{ route('sale-items.update', $item) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="quantity" :value="quantity">
                                    <input type="hidden" name="price" :value="price">
                                </form>

                                <!-- Delete Button -->
                                <form action="{{ route('sale-items.destroy', $item) }}" method="POST"
                                    onsubmit="return confirm('Delete this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $items->appends(['search' => $search ?? '', 'per_page' => $perPage ?? 10])->links() }}
        </div>
    </div>
</x-app-layout>
