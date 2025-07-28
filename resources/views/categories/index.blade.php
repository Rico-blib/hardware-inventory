<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Categories</h2>
    </x-slot>

    <div class="py-4 px-6">

        <!-- Flash Message -->
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Bar: Add, Show Entries, Search -->
        <div class="flex justify-between items-center mb-4">
            <!-- Add Button -->
            <button onclick="document.getElementById('addCategoryModal').showModal()"
                class="bg-green-800 hover:bg-green-900 text-white px-4 py-2 rounded">
                + Add New Category
            </button>


            <div class="flex items-center space-x-4">
                <!-- Show entries -->
                <form method="GET" action="{{ route('categories.index') }}">
                    <select name="per_page" onchange="this.form.submit()" class="border rounded px-2 py-1">
                        @foreach ([5, 10, 25, 50] as $size)
                            <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                                Show {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <!-- Search -->
                <form method="GET" action="{{ route('categories.index') }}">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search categories..."
                        class="border px-2 py-1 rounded">
                </form>
            </div>
        </div>


        <!-- Category Table -->
        <div class="overflow-x-auto">
            <table class="w-full bg-white shadow rounded">
                <thead class="bg-gray-800 text-white text-left">
                    <tr>
                        <th class="px-4 py-2">Id</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr class="border-t" x-data="{ editMode: false, name: '{{ $category->name }}' }">
                            <td class="px-4 py-2">{{ $category->id }}</td>
                            <td class="px-4 py-2">
                                <span x-show="!editMode" x-text="name"></span>
                                <form x-show="editMode" method="POST"
                                    action="{{ route('categories.update', $category) }}" class="inline">
                                    @csrf @method('PUT')
                                    <input type="text" name="name" x-model="name"
                                        class="border px-2 py-1 rounded w-32">
                                    <button class="text-blue-600 ml-2" @click="editMode = false">Update</button>
                                </form>
                            </td>
                            <td class="px-4 py-2 flex items-center space-x-2">
                                <!-- Edit Button -->
                                <button @click="editMode = !editMode" class="text-blue-600 hover:text-blue-800">
                                    <!-- Pencil Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2l-6 6m0 0L4 16l1-5 5-1z" />
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('categories.destroy', $category) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800">
                                        <!-- Trash Icon -->
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
            {{ $categories->appends(['per_page' => $perPage])->links() }}
        </div>
    </div>

    <!-- Modal -->
    <dialog id="addCategoryModal"
        class="rounded-lg p-6 w-1/3 absolute top-16 mx-auto left-0 right-0 z-50 bg-white shadow-lg">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <h3 class="text-lg font-semibold mb-4">Add New Category</h3>
            <input type="text" name="name" placeholder="Category name"
                class="w-full border px-3 py-2 rounded mb-4" required>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('addCategoryModal').close()"
                    class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
            </div>
        </form>
    </dialog>
</x-app-layout>
