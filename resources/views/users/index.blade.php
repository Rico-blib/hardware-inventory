<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">User Management</h2>
    </x-slot>

    <div class="py-6 px-6">
        <!-- Add User Button -->
        <div class="flex justify-end mb-4">
            <a href="{{ route('users.create') }}"
                class="bg-green-800 hover:bg-green-900 text-white font-semibold px-4 py-2 rounded shadow">
                + Add User
            </a>
        </div>

        <!-- Users Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded shadow text-sm">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Avatar</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2 text-left">Created At</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b">
                            <td class="px-4 py-2">
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                        class="h-8 w-8 rounded-full object-cover">
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2 capitalize">{{ $user->role }}</td>
                            <td class="px-4 py-2">{{ $user->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this user?')"
                                        class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
