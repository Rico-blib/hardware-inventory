<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Add New Purchase</h2>
    </x-slot>

    <div class="py-4 px-6">
        <form method="POST" action="{{ route('purchases.store') }}">
            @csrf

            <!-- Supplier -->
            <div class="mb-4">
                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                <select name="supplier_id" required class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                    <option value="">Select supplier</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Invoice Number -->
            <div class="mb-4">
                <label for="invoice_no" class="block text-sm font-medium text-gray-700">Invoice No</label>
                <input type="text" name="invoice_no" required
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm" />
            </div>

            <!-- Date -->
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Purchase Date</label>
                <input type="date" name="date" required
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm" />
            </div>

            <!-- Payment Status -->
            <div class="mb-4">
                <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                <select name="payment_status" class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                    <option value="unpaid">Unpaid</option>
                    <option value="partial">Partial</option>
                    <option value="paid">Paid</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-green-800 hover:bg-green-900 text-white font-semibold px-4 py-2 rounded shadow">
                    Next: Add Purchase Items →
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
