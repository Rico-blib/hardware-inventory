<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Application Settings
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6">
        @if (session('success'))
            <div class="mb-4 text-green-600 bg-green-100 p-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data"
            class="space-y-6 bg-white p-6 shadow rounded-xl">
            @csrf

            <div>
                <label class="block font-medium text-sm text-gray-700">App Name</label>
                <input type="text" name="app_name" value="{{ old('app_name', $setting->app_name ?? '') }}"
                    class="w-full mt-1 p-2 border rounded-md" placeholder="Enter your app name">
            </div>

            <div>
                <label class="block font-medium text-sm text-gray-700">Upload Logo</label>
                <input type="file" name="logo" class="mt-1 block w-full text-sm text-gray-700">
                @if (!empty($setting->logo_path))
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $setting->logo_path) }}" alt="Logo" class="h-12">
                    </div>
                @endif
            </div>

            <div>
                <label class="block font-medium text-sm text-gray-700">Contact Number</label>
                <input type="text" name="contact_number"
                    value="{{ old('contact_number', $setting->contact_number ?? '') }}"
                    class="w-full mt-1 p-2 border rounded-md" placeholder="e.g. +254712345678">
            </div>
            <div>
                <label class="block font-medium text-sm text-gray-700">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $setting->email ?? '') }}"
                    class="w-full mt-1 p-2 border rounded-md" placeholder="e.g. info@yourcompany.com">
            </div>

            <div>
                <label class="block font-medium text-sm text-gray-700">Login Background Color</label>
                <input type="color" name="login_background_color"
                    value="{{ old('login_background_color', $setting->login_background_color ?? '#ffffff') }}"
                    class="w-16 h-10 mt-1 border rounded">
            </div>

            <div>
                <label class="block font-medium text-sm text-gray-700">Login Background Image</label>
                <input type="file" name="login_background_image" class="mt-1 block w-full text-sm text-gray-700">
                @if (!empty($setting->login_background_image))
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $setting->login_background_image) }}" alt="Background Image"
                            class="h-20 rounded-md">
                    </div>
                @endif
            </div>

            <div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
