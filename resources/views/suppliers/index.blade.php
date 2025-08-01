<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Suppliers</h2>
    </x-slot>

    <div class="py-4 px-6">

        <!-- Flash Message -->
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Controls: Add, Show, Search -->
        <div class="flex justify-between items-center mb-4">
            <button onclick="document.getElementById('addSupplierModal').showModal()"
                class="bg-green-800 hover:bg-green-900 text-white px-4 py-2 rounded">
                + Add New Supplier
            </button>

            <div class="flex items-center space-x-4">
                <!-- Show entries -->
                <form method="GET" action="{{ route('suppliers.index') }}">
                    <select name="per_page" onchange="this.form.submit()" class="border rounded px-2 py-1">
                        @foreach ([5, 10, 25, 50] as $size)
                            <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                                Show {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Search -->
                <form method="GET" action="{{ route('suppliers.index') }}">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search suppliers..."
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
                        <th class="px-4 py-2">Address</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Phone</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                        <tr class="border-t" x-data="{ editMode: false }">
                            <td class="px-4 py-2">{{ $supplier->id }}</td>

                            <!-- Name -->
                            <td class="px-4 py-2">
                                <span x-show="!editMode">{{ $supplier->name }}</span>
                                <form x-show="editMode" method="POST"
                                    action="{{ route('suppliers.update', $supplier) }}">
                                    @csrf @method('PUT')
                                    <input type="text" name="name" value="{{ $supplier->name }}"
                                        class="border px-2 py-1 w-full">
                            </td>

                            <!-- Address -->
                            <td class="px-4 py-2">
                                <span x-show="!editMode">{{ $supplier->address }}</span>
                                <input x-show="editMode" type="text" name="address" value="{{ $supplier->address }}"
                                    class="border px-2 py-1 w-full">
                            </td>

                            <!-- Email -->
                            <td class="px-4 py-2">
                                <span x-show="!editMode">{{ $supplier->email }}</span>
                                <input x-show="editMode" type="email" name="email" value="{{ $supplier->email }}"
                                    class="border px-2 py-1 w-full">
                            </td>

                            <!-- Phone -->
                            <td class="px-4 py-2">
                                <span x-show="!editMode">{{ $supplier->phone }}</span>
                                <input x-show="editMode" type="text" name="phone" value="{{ $supplier->phone }}"
                                    class="border px-2 py-1 w-full">
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-2 flex gap-2 items-center">
                                <div x-show="editMode" class="flex gap-1">
                                    <button type="submit" class="text-green-600 hover:text-green-800">✅</button>
                                    </form> <!-- close the update form here -->
                                    <button @click="editMode = false" class="text-gray-500">✖</button>
                                </div>

                                <div x-show="!editMode" class="flex gap-1">
                                    <button @click="editMode = true"
                                        class="text-blue-600 hover:text-blue-800">✏️</button>
                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?')">
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
            {{ $suppliers->appends(['search' => $search, 'per_page' => $perPage])->links() }}
        </div>
    </div>

    <!-- Modal -->
    <dialog id="addSupplierModal"
        class="rounded-lg p-6 w-1/2 absolute top-16 mx-auto left-0 right-0 z-50 bg-white shadow-lg">
        <form method="POST" action="{{ route('suppliers.store') }}">
            @csrf
            <h3 class="text-lg font-semibold mb-4">Add New Supplier</h3>
            <div class="mb-3">
                <input type="text" name="name" placeholder="Name" class="w-full border px-3 py-2 rounded"
                    required>
            </div>
            <div class="mb-3">
                <input type="text" name="address" placeholder="Address" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-3">
                <input type="email" name="email" placeholder="Email" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-3">
                <input type="text" name="phone" placeholder="Phone" class="w-full border px-3 py-2 rounded">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('addSupplierModal').close()"
                    class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button class="px-4 py-2 bg-green-800 hover:bg-green-900 text-white rounded">Save</button>
            </div>
        </form>
    </dialog>
</x-app-layout>
