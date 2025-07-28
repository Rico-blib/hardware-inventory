<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Add Items to Purchase #{{ request('purchase_id') }}</h2>
    </x-slot>

    <div class="py-4 px-6">
        <!-- Flash message -->
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('purchase-items.store') }}">
            @csrf

            <!-- Hidden purchase_id -->
            <input type="hidden" name="purchase_id" value="{{ request('purchase_id') }}">

            <!-- Product -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Product</label>
                <select name="product_id" required class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                    <option value="">Select product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->name }} ({{ $product->packaging }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quantity -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" name="quantity" required min="1"
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">
            </div>

            <!-- Cost Price -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Cost Price (per unit)</label>
                <input type="number" step="0.01" name="cost_price" required
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">
            </div>

            <!-- Selling Price -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Selling Price (per unit)</label>
                <input type="number" step="0.01" name="selling_price"
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">
            </div>

            <!-- Batch No (optional) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Batch No</label>
                <input type="text" name="batch_no" class="mt-1 block w-full border-gray-300 rounded shadow-sm">
            </div>

            <!-- Expiry Date (optional) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                <input type="date" name="expiry_date" class="mt-1 block w-full border-gray-300 rounded shadow-sm">
            </div>

            <!-- Submit -->
            <div class="flex justify-between items-center">
                <a href="{{ route('purchases.index') }}" class="text-blue-700">← Finish</a>
                <button type="submit" class="bg-green-800 hover:bg-green-900 text-white px-4 py-2 rounded shadow">
                    + Add Item
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
