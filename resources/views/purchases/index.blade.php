<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Purchase List</h2>
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
        <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
            <div class="space-x-2">
                <a href="{{ route('purchases.create') }}"
                    class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded shadow">
                    + Add Purchase
                </a>

            </div>

            <div class="flex items-center gap-4 flex-wrap">

                <!-- Search -->
                <form method="GET" action="{{ route('purchases.index') }}">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search invoice/supplier"
                        class="border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300" />
                </form>

                <!-- Show entries -->
                <form method="GET" action="{{ route('purchases.index') }}">
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
                        <th class="px-4 py-2 text-left">Supplier</th>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Total</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                        <tr x-data="{
                            editMode: false,
                            form: {
                                invoice_no: '{{ $purchase->invoice_no }}',
                                date: '{{ $purchase->date }}',
                                payment_status: '{{ $purchase->payment_status }}'
                            },
                            submit() {
                                this.$refs.form.submit();
                            }
                        }">
                            <!-- Hidden Update Form -->
                            <form method="POST" :action="'{{ route('purchases.update', $purchase) }}'" x-ref="form"
                                class="hidden">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="invoice_no" :value="form.invoice_no">
                                <input type="hidden" name="date" :value="form.date">
                                <input type="hidden" name="payment_status" :value="form.payment_status">
                            </form>

                            <!-- ID -->
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>

                            <!-- Invoice -->
                            <td class="px-4 py-2">
                                <template x-if="!editMode">
                                    <span x-text="form.invoice_no"></span>
                                </template>
                                <template x-if="editMode">
                                    <input type="text" x-model="form.invoice_no" class="border px-2 py-1 w-full"
                                        required>
                                </template>
                            </td>

                            <!-- Supplier (not editable) -->
                            <td class="px-4 py-2">
                                {{ $purchase->supplier->name }}
                            </td>

                            <!-- Date -->
                            <td class="px-4 py-2">
                                <template x-if="!editMode">
                                    <span x-text="form.date"></span>
                                </template>
                                <template x-if="editMode">
                                    <input type="date" x-model="form.date" class="border px-2 py-1 w-full" required>
                                </template>
                            </td>

                            <!-- Total -->
                            <td class="px-4 py-2">
                                Ksh {{ number_format($purchase->total, 2) }}
                            </td>

                            <!-- Payment Status -->
                            <td class="px-4 py-2">
                                <template x-if="!editMode">
                                    <span x-text="form.payment_status"></span>
                                </template>
                                <template x-if="editMode">
                                    <select x-model="form.payment_status" class="border px-2 py-1 w-full">
                                        <option value="unpaid">Unpaid</option>
                                        <option value="partial">Partial</option>
                                        <option value="paid">Paid</option>
                                    </select>
                                </template>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-2 text-center">
                                <div class="flex justify-center gap-2">
                                    <!-- View -->
                                    <a href="{{ route('purchases.show', $purchase) }}"
                                        class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded">
                                        <x-heroicon-o-eye class="w-5 h-5" />
                                    </a>

                                    <!-- Edit / Save -->
                                    <template x-if="editMode">
                                        <button type="button" @click="submit()"
                                            class="bg-green-600 text-white px-2 py-1 rounded">
                                            💾
                                        </button>
                                    </template>
                                    <template x-if="!editMode">
                                        <button type="button" @click="editMode = true"
                                            class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded">
                                            <x-heroicon-o-pencil-square class="w-5 h-5" />
                                        </button>
                                    </template>

                                    <!-- Delete -->
                                    <form action="{{ route('purchases.destroy', $purchase) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this purchase?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $purchases->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
