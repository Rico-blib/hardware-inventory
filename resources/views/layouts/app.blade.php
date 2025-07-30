<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> {{ $globalSetting?->app_name ?? config('app.name', 'POS System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if ($globalSetting?->logo_path)
        <link rel="icon" href="{{ asset('storage/' . $globalSetting->logo_path) }}" type="image/png">
    @endif
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0">
            <div class="p-4 text-xl font-bold border-b border-gray-800">
                {{ $globalSetting?->app_name ?? config('app.name', 'Inventory System') }}
            </div>
            <nav class="mt-4">
                <ul class="space-y-1 p-4 text-sm">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 transition">
                            🏠 Dashboard
                        </a>
                    </li>

                    {{-- Only for super-admin --}}
                    @if (auth()->user()->role === 'super-admin')
                        <li><a href="{{ route('categories.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 transition">📂
                                Categories</a></li>
                        <li><a href="{{ route('products.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 transition">📦
                                Products</a></li>
                        <li><a href="{{ route('customers.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 transition">👥
                                Customers</a></li>
                        <li><a href="{{ route('suppliers.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 transition">🏭
                                Suppliers</a></li>
                    @endif

                    <!-- Sales -->
                    <li x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full flex justify-between items-center px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                            <span>🛒 Sales</span>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="open" class="mt-2 space-y-1 pl-4">
                            <li><a href="{{ route('sales.create') }}"
                                    class="block px-2 py-1 text-gray-700 hover:bg-gray-200 rounded">➕ Add Sale</a></li>
                            <li><a href="{{ route('sales.index') }}"
                                    class="block px-2 py-1 text-gray-700 hover:bg-gray-200 rounded">📄 Sales List</a>
                            </li>

                            @if (auth()->user()->role === 'super-admin')
                                <li><a href="{{ route('sale-items.index') }}"
                                        class="block px-2 py-1 text-gray-700 hover:bg-gray-200 rounded">📦 Sale
                                        Items</a></li>
                            @endif
                        </ul>
                    </li>

                    {{-- Purchases (only for super-admin) --}}
                    @if (auth()->user()->role === 'super-admin')
                        <li x-data="{ open: false }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-gray-800 transition">
                                <div class="flex items-center gap-3">📥 Purchases</div>
                                <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="open" x-transition class="ml-7 mt-2 space-y-1 text-sm text-gray-300">
                                <li><a href="{{ route('purchases.create') }}"
                                        class="block px-2 py-1 rounded hover:bg-gray-800">+ Add Purchase</a></li>
                                <li><a href="{{ route('purchases.index') }}"
                                        class="block px-2 py-1 rounded hover:bg-gray-800">📄 Purchase List</a></li>
                                <li><a href="{{ route('purchase-items.index') }}"
                                        class="block px-2 py-1 rounded hover:bg-gray-800">📦 Purchase Items</a></li>
                            </ul>
                        </li>
                    @endif

                    <!-- Reports -->
                    <li x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-3 py-2 rounded hover:bg-gray-800 transition">
                            <div class="flex items-center gap-3">📊 Reports</div>
                            <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200"
                                :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="open" x-transition class="ml-7 mt-2 space-y-1 text-sm text-gray-300">
                            <li><a href="{{ route('reports.sales') }}"
                                    class="block px-2 py-1 rounded hover:bg-gray-800">📈 Sales Report</a></li>
                            <li><a href="{{ route('reports.purchases') }}"
                                    class="block px-2 py-1 rounded hover:bg-gray-800">📥 Purchase Report</a></li>
                        </ul>
                    </li>

                    {{-- User Manager (super-admin only) --}}
                    @if (auth()->user()->role === 'super-admin')
                        <li>
                            <a href="{{ route('users.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 transition">
                                👤 User Manager
                            </a>
                        </li>
                    @endif

                    <!-- Settings (optional) -->
                    @if (auth()->user()->role === 'super-admin')
                        <li>
                            <a href="{{ route('settings.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-800 transition">
                                ⚙️ Settings
                            </a>
                        </li>
                    @endif

                </ul>
            </nav>

        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Optional Top Nav -->
            <header class="bg-white shadow">
                @include('layouts.navigation')
            </header>

            <main class="p-6">
                @isset($header)
                    <div class="mb-4 text-xl font-semibold text-gray-700">
                        {{ $header }}
                    </div>
                @endisset

                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')

</body>

</html>
