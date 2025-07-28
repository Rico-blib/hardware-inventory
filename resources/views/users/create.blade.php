<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Add New User</h2>
    </x-slot>

    <div class="py-6 px-6 max-w-xl">
        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                    Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" id="role" required
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                    <option value="sales-person" selected>Sales Person</option>
                    <option value="super-admin">Super Admin</option> <!-- For manual/admin use only -->
                </select>
                <p class="text-sm text-gray-500 mt-1">Use "Super Admin" only if authorized.</p>
            </div>

            <!-- Avatar -->
            <div class="mb-4">
                <label for="avatar" class="block text-sm font-medium text-gray-700">Avatar (optional)</label>
                <input type="file" name="avatar" id="avatar"
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">
                @error('avatar')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-green-800 hover:bg-green-900 text-white font-semibold px-4 py-2 rounded shadow">
                    Save User
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
