<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Purchase Items</h2>
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
            <a href="{{ route('purchases.index') }}" class="bg-green-800 hover:bg-green-900 text-white px-4 py-2 rounded">
                + Add Purchase Item
            </a>

            <div class="flex items-center space-x-4">
                <!-- Entries per page -->
                <form method="GET" action="{{ route('purchase-items.index') }}">
                    <select name="per_page" onchange="this.form.submit()" class="border rounded px-2 py-1">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ ($perPage ?? 10) == $size ? 'selected' : '' }}>
                                Show {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Search -->
                <form method="GET" action="{{ route('purchase-items.index') }}">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Search product/batch..." class="border px-2 py-1 rounded">
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
                        <th class="px-4 py-2">Supplier</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Cost Price</th>
                        <th class="px-4 py-2">Selling Price</th>
                        <th class="px-4 py-2">Batch No</th>
                        <th class="px-4 py-2">Expiry Date</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr class="border-t" x-data="{
                            editMode: false,
                            form: {
                                quantity: '{{ $item->quantity }}',
                                cost_price: '{{ $item->cost_price }}',
                                selling_price: '{{ $item->selling_price }}',
                                batch_no: '{{ $item->batch_no }}',
                                expiry_date: '{{ $item->expiry_date }}'
                            }
                        }">
                            <td class="px-4 py-2">{{ $item->id }}</td>
                            <td class="px-4 py-2">{{ $item->product->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $item->purchase->supplier->name ?? 'N/A' }}</td>

                            <td class="px-4 py-2">
                                <template x-if="!editMode">
                                    <span x-text="form.quantity"></span>
                                </template>
                                <template x-if="editMode">
                                    <input type="number" x-model="form.quantity" class="border px-2 py-1 w-full">
                                </template>
                            </td>

                            <td class="px-4 py-2">
                                <template x-if="!editMode">
                                    <span x-text="form.cost_price"></span>
                                </template>
                                <template x-if="editMode">
                                    <input type="number" step="0.01" x-model="form.cost_price"
                                        class="border px-2 py-1 w-full">
                                </template>
                            </td>

                            <td class="px-4 py-2">
                                <template x-if="!editMode">
                                    <span x-text="form.selling_price"></span>
                                </template>
                                <template x-if="editMode">
                                    <input type="number" step="0.01" x-model="form.selling_price"
                                        class="border px-2 py-1 w-full">
                                </template>
                            </td>

                            <td class="px-4 py-2">
                                <template x-if="!editMode">
                                    <span x-text="form.batch_no"></span>
                                </template>
                                <template x-if="editMode">
                                    <input type="text" x-model="form.batch_no" class="border px-2 py-1 w-full">
                                </template>
                            </td>

                            <td class="px-4 py-2">
                                <template x-if="!editMode">
                                    <span x-text="form.expiry_date"></span>
                                </template>
                                <template x-if="editMode">
                                    <input type="date" x-model="form.expiry_date" class="border px-2 py-1 w-full">
                                </template>
                            </td>

                            <td class="px-4 py-2 flex space-x-2">
                                <template x-if="!editMode">
                                    <button type="button" @click="editMode = true"
                                        class="text-blue-600 hover:text-blue-800">✏️</button>
                                </template>
                                <template x-if="editMode">
                                    <form method="POST" action="{{ route('purchase-items.update', $item) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="quantity" :value="form.quantity">
                                        <input type="hidden" name="cost_price" :value="form.cost_price">
                                        <input type="hidden" name="selling_price" :value="form.selling_price">
                                        <input type="hidden" name="batch_no" :value="form.batch_no">
                                        <input type="hidden" name="expiry_date" :value="form.expiry_date">
                                        <button type="submit" class="text-green-600 hover:text-green-800">✅</button>
                                    </form>
                                </template>

                                <form action="{{ route('purchase-items.destroy', $item) }}" method="POST"
                                    onsubmit="return confirm('Delete this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">🗑️</button>
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
