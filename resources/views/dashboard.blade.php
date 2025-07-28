<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
    </x-slot>

    <div class="py-6 px-4 mx-auto max-w-7xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">

            <!-- Today's Sales and Purchases -->
            <div class="bg-white rounded-2xl shadow p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-500">Today’s Activity</h3>
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-xl font-bold text-green-600">Ksh {{ number_format($salesToday + $purchasesToday, 2) }}
                </p>
                <div class="mt-2">
                    <p class="text-sm text-gray-600">Sales: <span class="text-blue-600 font-semibold">Ksh
                            {{ number_format($salesToday, 2) }}</span></p>
                    <p class="text-sm text-gray-600">Purchases: <span class="text-red-500 font-semibold">Ksh
                            {{ number_format($purchasesToday, 2) }}</span></p>
                </div>
                <div class="mt-2">
                    <div class="h-2 rounded-full bg-gray-200">
                        <div class="h-full rounded-full bg-green-500"
                            style="width: {{ $salesToday + $purchasesToday > 0 ? ($salesToday / ($salesToday + $purchasesToday)) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Categories -->
            <div class="bg-white rounded-2xl shadow p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-500">Available Categories</h3>
                    <a href="#" class="text-xs text-indigo-500 hover:underline">View All</a>
                </div>
                <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $categoryCount }}</p>
            </div>

            <!-- Expired Products -->
            <div class="bg-red-100 rounded-2xl shadow p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-red-700">Expired Products</h3>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#expiredModal" id="loadExpired">See Expired
                        Items</a>
                </div>
                <p class="text-3xl font-bold text-red-700 mt-2">{{ $expiredProductsCount }}</p>
            </div>

            <!-- Low Stock Products -->
            <div class="bg-yellow-100 rounded-2xl shadow p-5">
                <h3 class="text-sm font-semibold text-yellow-800">Low Stock Products</h3>
                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $lowStockCount }}</p>
                <a href="#" data-bs-toggle="modal" data-bs-target="#lowStockModal" id="loadLowStock">See Low
                    Stock</a>
            </div>

            <!-- Users -->
            <div class="bg-white rounded-2xl shadow p-5">
                <h3 class="text-sm font-semibold text-gray-500 mb-1">Users</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $userCount }}</p>
                <p class="text-xs text-gray-500">Super-admins & Sales-persons</p>
            </div>

            <!-- Customers -->
            <div class="bg-white rounded-2xl shadow p-5">
                <h3 class="text-sm font-semibold text-gray-500 mb-1">Customers</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $customerCount }}</p>
                <p class="text-xs text-gray-500">New this week: 8</p>
            </div>

            <!-- Useful Links -->
            <div class="bg-white rounded-2xl shadow p-5 col-span-1 sm:col-span-2 md:col-span-3 xl:col-span-4">
                <h3 class="text-sm font-semibold text-gray-500 mb-3">Useful Links</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('sales.create') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">Add Sale</a>
                    <a href="{{ route('purchases.create') }}"
                        class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600">Add Purchase</a>
                    <a href="{{ route('reports.sales') }}"
                        class="bg-purple-500 text-white px-4 py-2 rounded shadow hover:bg-purple-600">Sales Report</a>
                    <a href="{{ route('reports.purchases') }}"
                        class="bg-orange-500 text-white px-4 py-2 rounded shadow hover:bg-orange-600">Purchases
                        Report</a>
                    <a href="{{ route('users.index') }}"
                        class="bg-gray-700 text-white px-4 py-2 rounded shadow hover:bg-gray-800">Manage Users</a>
                </div>
            </div>

            <!-- Optional Sales Chart (Placeholder) -->
            <div class="bg-white rounded-2xl shadow p-5 col-span-full">
                <h3 class="text-sm font-semibold text-gray-500 mb-3">Sales (Last 7 Days)</h3>
                <div class="relative h-64">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Expired Modal -->
    <div class="modal fade" id="expiredModal" tabindex="-1" aria-labelledby="expiredModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="expiredModalLabel">Expired Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="expiredItemsContent">Loading...</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Modal -->
    <div class="modal fade" id="lowStockModal" tabindex="-1" aria-labelledby="lowStockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lowStockModalLabel">Low Stock Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="lowStockContent">Loading...</div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Sales (KES)',
                        data: [1200, 1900, 3000, 2500, 2200, 1800, 2400],
                        backgroundColor: 'rgba(99, 102, 241, 0.2)',
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // AJAX for modals
            document.getElementById('loadExpired')?.addEventListener('click', function() {
                fetch("{{ route('dashboard.expired-items') }}")
                    .then(res => res.text())
                    .then(data => document.getElementById('expiredItemsContent').innerHTML = data);
            });

            document.getElementById('loadLowStock')?.addEventListener('click', function() {
                fetch("{{ route('dashboard.low-stock-items') }}")
                    .then(res => res.text())
                    .then(data => document.getElementById('lowstockContent').innerHTML = data);
            });
        </script>
    @endpush
</x-app-layout>
