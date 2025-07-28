<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit User</h2>
    </x-slot>

    <div class="py-4 px-6 max-w-2xl mx-auto">
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" class="mt-1 block w-full border-gray-300 rounded shadow-sm" required>
                    <option value="sales-person" {{ $user->role === 'sales-person' ? 'selected' : '' }}>Sales Person
                    </option>
                    <option value="super-admin" {{ $user->role === 'super-admin' ? 'selected' : '' }}>Super Admin
                    </option>
                </select>
            </div>

            <!-- Avatar -->
            <div class="mb-4">
                <label for="avatar" class="block text-sm font-medium text-gray-700">Avatar</label>
                @if ($user->avatar)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Current Avatar"
                            class="w-16 h-16 rounded-full object-cover">
                    </div>
                @endif
                <input type="file" name="avatar" class="mt-1 block w-full text-sm text-gray-700">
                <p class="text-sm text-gray-500 mt-1">Leave blank to keep existing avatar.</p>
            </div>

            <!-- Update Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">
                    Update User
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
