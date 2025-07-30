<x-guest-layout>
    <div class="max-w-md mx-auto mt-20 bg-white shadow p-6 rounded">
        <h2 class="text-xl font-bold mb-4">Activate Your POS</h2>

        @if (session('success'))
            <div class="bg-green-100 p-3 mb-4 rounded">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('activate.license') }}">
            @csrf
            <label for="key" class="block font-medium">Activation Key</label>
            <input type="text" name="key" id="key" class="form-input mt-1 block w-full" required>

            @error('key')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded">Activate</button>
        </form>
    </div>
</x-guest-layout>
