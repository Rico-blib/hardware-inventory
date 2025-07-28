<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Customers</h2>
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
            <button onclick="document.getElementById('addCustomerModal').showModal()"
                class="bg-green-800 hover:bg-green-900 text-white px-4 py-2 rounded">
                + Add New Customer
            </button>

            <div class="flex items-center space-x-4">
                <!-- Entries selector -->
                <form method="GET" action="{{ route('customers.index') }}">
                    <select name="per_page" onchange="this.form.submit()" class="border rounded px-2 py-1">
                        @foreach ([5, 10, 25, 50] as $size)
                            <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                                Show {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Search input -->
                <form method="GET" action="{{ route('customers.index') }}">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search customers..."
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
                    @foreach ($customers as $customer)
                        <tr class="border-t" x-data="{
                            editMode: false,
                            form: {
                                name: '{{ $customer->name }}',
                                address: '{{ $customer->address }}',
                                email: '{{ $customer->email }}',
                                phone: '{{ $customer->phone }}'
                            }
                        }">
                            <td class="px-4 py-2">{{ $customer->id }}</td>

                            <td class="px-4 py-2">
                                <span x-show="!editMode" x-text="form.name"></span>
                                <form x-show="editMode" method="POST"
                                    action="{{ route('customers.update', $customer) }}">
                                    @csrf @method('PUT')
                                    <input type="text" name="name" x-model="form.name"
                                        class="border px-2 py-1 w-full">
                                </form>
                            </td>

                            <td class="px-4 py-2">
                                <span x-show="!editMode" x-text="form.address"></span>
                                <form x-show="editMode" method="POST"
                                    action="{{ route('customers.update', $customer) }}">
                                    @csrf @method('PUT')
                                    <input type="text" name="address" x-model="form.address"
                                        class="border px-2 py-1 w-full">
                                </form>
                            </td>

                            <td class="px-4 py-2">
                                <span x-show="!editMode" x-text="form.email"></span>
                                <form x-show="editMode" method="POST"
                                    action="{{ route('customers.update', $customer) }}">
                                    @csrf @method('PUT')
                                    <input type="email" name="email" x-model="form.email"
                                        class="border px-2 py-1 w-full">
                                </form>
                            </td>

                            <td class="px-4 py-2">
                                <span x-show="!editMode" x-text="form.phone"></span>
                                <form x-show="editMode" method="POST"
                                    action="{{ route('customers.update', $customer) }}">
                                    @csrf @method('PUT')
                                    <input type="text" name="phone" x-model="form.phone"
                                        class="border px-2 py-1 w-full">
                                </form>
                            </td>

                            <td class="px-4 py-2 flex space-x-2">
                                <!-- Edit icon -->
                                <button @click="editMode = !editMode" class="text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2l-6 6m0 0L4 16l1-5 5-1z" />
                                    </svg>
                                </button>

                                <!-- Delete icon -->
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $customers->appends(['search' => $search, 'per_page' => $perPage])->links() }}
        </div>
    </div>

    <!-- Modal -->
    <dialog id="addCustomerModal"
        class="rounded-lg p-6 w-1/2 absolute top-16 mx-auto left-0 right-0 z-50 bg-white shadow-lg">
        <form method="POST" action="{{ route('customers.store') }}">
            @csrf
            <h3 class="text-lg font-semibold mb-4">Add New Customer</h3>
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
                <button type="button" onclick="document.getElementById('addCustomerModal').close()"
                    class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button class="px-4 py-2 bg-green-800 hover:bg-green-900 text-white rounded">Save</button>
            </div>
        </form>
    </dialog>
</x-app-layout>
