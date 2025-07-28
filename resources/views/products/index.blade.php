<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Products</h2>
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
            <button onclick="document.getElementById('addProductModal').showModal()"
                class="bg-green-800 hover:bg-green-900 text-white px-4 py-2 rounded">
                + Add New Product
            </button>

            <div class="flex items-center space-x-4">
                <!-- Entries selector -->
                <form method="GET" action="{{ route('products.index') }}">
                    <select name="per_page" onchange="this.form.submit()" class="border rounded px-2 py-1">
                        @foreach ([5, 10, 25, 50] as $size)
                            <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                                Show {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Search input -->
                <form method="GET" action="{{ route('products.index') }}">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search products..."
                        class="border px-2 py-1 rounded">
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full bg-white shadow rounded">
                <thead class="bg-gray-800 text-white text-left">
                    <tr>
                        <th class="px-4 py-2">Id</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Category</th>
                        <th class="px-4 py-2">Packaging</th>
                        <th class="px-4 py-2">Manufacturer</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="border-t" x-data="{ editMode: false }">
                            <td class="px-4 py-2">{{ $product->id }}</td>

                            <!-- Name -->
                            <td class="px-4 py-2">
                                <span x-show="!editMode">{{ $product->name }}</span>
                                <form x-show="editMode" method="POST"
                                    action="{{ route('products.update', $product) }}">
                                    @csrf @method('PUT')
                                    <input type="text" name="name" value="{{ $product->name }}"
                                        class="border px-2 py-1 w-full">
                            </td>

                            <!-- Category -->
                            <td class="px-4 py-2">
                                <span x-show="!editMode">{{ $product->category->name ?? '—' }}</span>
                                <select x-show="editMode" name="category_id" class="border px-2 py-1 w-full">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <!-- Packaging -->
                            <td class="px-4 py-2">
                                <span x-show="!editMode">{{ $product->packaging }}</span>
                                <input x-show="editMode" type="text" name="packaging"
                                    value="{{ $product->packaging }}" class="border px-2 py-1 w-full">
                            </td>

                            <!-- Manufacturer -->
                            <td class="px-4 py-2">
                                <span x-show="!editMode">{{ $product->manufacturer }}</span>
                                <input x-show="editMode" type="text" name="manufacturer"
                                    value="{{ $product->manufacturer }}" class="border px-2 py-1 w-full">
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-2 flex gap-2 items-center">
                                <div x-show="editMode" class="flex gap-1">
                                    <button type="submit" class="text-green-600 hover:text-green-800">
                                        ✅
                                    </button>
                                    </form>
                                    <button @click="editMode = false" class="text-gray-500">✖</button>
                                </div>
                                <div x-show="!editMode" class="flex gap-1">
                                    <button @click="editMode = true" class="text-blue-600 hover:text-blue-800">
                                        ✏️
                                    </button>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800">🗑️</button>
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
            {{ $products->appends(['search' => $search, 'per_page' => $perPage])->links() }}
        </div>
    </div>

    <!-- Modal -->
    <dialog id="addProductModal"
        class="rounded-lg p-6 w-1/2 absolute top-16 mx-auto left-0 right-0 z-50 bg-white shadow-lg">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf
            <h3 class="text-lg font-semibold mb-4">Add New Product</h3>

            <div class="mb-3">
                <input type="text" name="name" placeholder="Product name" class="w-full border px-3 py-2 rounded"
                    required>
            </div>

            <div class="mb-3">
                <select name="category_id" required class="w-full border px-3 py-2 rounded">
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <input type="text" name="packaging" placeholder="Packaging (e.g. 50kg bag)"
                    class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-3">
                <input type="text" name="manufacturer" placeholder="Manufacturer"
                    class="w-full border px-3 py-2 rounded">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('addProductModal').close()"
                    class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button class="px-4 py-2 bg-green-800 hover:bg-green-900 text-white rounded">Save</button>
            </div>
        </form>
    </dialog>
</x-app-layout>
