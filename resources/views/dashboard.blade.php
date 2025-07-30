<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
    </x-slot>

    <div class="py-6 px-4 mx-auto max-w-7xl" x-data="dashboardModals()" x-init>
        <!-- Grid Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">

            <!-- Today's Sales and Purchases -->
            <div class="bg-white rounded-2xl shadow p-5">
                <h3 class="text-sm font-semibold text-gray-500">Today’s Activity</h3>
                <p class="text-xl font-bold text-green-600">Ksh {{ number_format($salesToday + $purchasesToday, 2) }}</p>
                <div class="mt-2 text-sm text-gray-600">
                    <p>Sales: <span class="text-blue-600 font-semibold">Ksh {{ number_format($salesToday, 2) }}</span>
                    </p>
                    <p>Purchases: <span class="text-red-500 font-semibold">Ksh
                            {{ number_format($purchasesToday, 2) }}</span></p>
                </div>
            </div>

            <!-- Categories -->
            <div class="bg-white rounded-2xl shadow p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-500">Available Categories</h3>
                    <a href="{{ route('categories.index') }}" class="text-xs text-indigo-500 hover:underline">View
                        All</a>
                </div>
                <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $categoryCount }}</p>
            </div>

            <!-- Expired Products -->
            <div class="bg-red-100 rounded-2xl shadow p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-red-700">Expired Products</h3>
                    <button @click="openExpired()" class="text-xs text-red-600 hover:underline">See Expired
                        Items</button>
                </div>
                <p class="text-3xl font-bold text-red-700 mt-2">{{ $expiredProductsCount }}</p>
            </div>

            <!-- Low Stock Products -->
            <div class="bg-yellow-100 rounded-2xl shadow p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-yellow-800">Low Stock Products</h3>
                    <button @click="openLowStock()" class="text-xs text-yellow-700 hover:underline">See Low
                        Stock</button>
                </div>
                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $lowStockCount }}</p>
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
                <p class="text-xs text-gray-500">New this week: </p>
            </div>

            <!-- Top 5 Selling Products -->
            <div class="bg-green-100 rounded-2xl shadow p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-green-800">Top 5 Selling Products</h3>
                    <button @click="openTopSelling()" class="text-xs text-green-700 hover:underline">See Top
                        Products</button>
                </div>
                <p class="text-3xl font-bold text-green-700 mt-2">{{ $topSellingProducts->sum('total_sold') }}</p>
            </div>

            <!-- Bottom 5 Selling Products -->
            <div class="bg-orange-100 rounded-2xl shadow p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-orange-800">Bottom 5 Selling Products</h3>
                    <button @click="openBottomSelling()" class="text-xs text-orange-700 hover:underline">See Bottom
                        Products</button>
                </div>
                <p class="text-3xl font-bold text-orange-700 mt-2">{{ $bottomSellingProducts->sum('total_sold') }}</p>
            </div>


            <!-- Useful Links -->
            <div class="bg-white rounded-2xl shadow p-5 col-span-full">
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

            <!-- CHARTS ROW -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 col-span-full">
                <!-- Sales Chart -->
                <div class="bg-white rounded-2xl shadow p-5">
                    <h3 class="text-sm font-semibold text-gray-500 mb-3">Sales (Last 7 Days)</h3>
                    <div class="relative h-64">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Category Pie Chart -->
                <div class="bg-white rounded-2xl shadow p-5">
                    <h3 class="text-sm font-semibold text-gray-500 mb-3">Category Distribution</h3>
                    <div class="relative h-64">
                        <canvas id="categoryPieChart"></canvas>
                    </div>
                </div>
            </div>


        </div>

        <!-- MODALS -->
        <dialog x-ref="expiredModal" class="rounded-xl shadow-lg w-96 p-4 border border-red-200">
            <h2 class="text-lg font-semibold mb-2">Expired Products</h2>
            <div id="expiredItemsContent" class="text-sm text-gray-700">Loading...</div>
            <div class="mt-4 text-right">
                <button @click="closeExpired()"
                    class="px-4 py-2 text-white bg-gray-600 hover:bg-gray-700 rounded">Close</button>
            </div>
        </dialog>

        <dialog x-ref="lowStockModal" class="rounded-xl shadow-lg w-96 p-4 border border-yellow-200">
            <h2 class="text-lg font-semibold mb-2">Low Stock Products</h2>
            <div id="lowStockContent" class="text-sm text-gray-700">Loading...</div>
            <div class="mt-4 text-right">
                <button @click="closeLowStock()"
                    class="px-4 py-2 text-white bg-gray-600 hover:bg-gray-700 rounded">Close</button>
            </div>
        </dialog>
        <!-- Top Selling Modal -->
        <dialog x-ref="topSellingModal" class="rounded-xl shadow-lg w-[40rem] p-4 border border-green-200">
            <h2 class="text-lg font-semibold mb-2">Top Selling Products</h2>
            <div id="topSellingContent" class="text-sm text-gray-700 overflow-x-auto">Loading...</div>
            <div class="mt-4 text-right">
                <button @click="closeTopSelling()"
                    class="px-4 py-2 text-white bg-gray-600 hover:bg-gray-700 rounded">Close</button>
            </div>
        </dialog>
        <!-- Bottom Selling Modal -->
        <dialog x-ref="bottomSellingModal" class="rounded-xl shadow-lg w-[40rem] p-4 border border-orange-200">
            <h2 class="text-lg font-semibold mb-2">Bottom Selling Products</h2>
            <div id="bottomSellingContent" class="text-sm text-gray-700 overflow-x-auto">Loading...</div>
            <div class="mt-4 text-right">
                <button @click="closeBottomSelling()"
                    class="px-4 py-2 text-white bg-gray-600 hover:bg-gray-700 rounded">Close</button>
            </div>
        </dialog>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // Chart
            // Sales Line Chart (Past 7 Days)
            const salesCtx = document.getElementById('salesChart').getContext('2d');

            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: @json($last7Days), // e.g. ['Mon', 'Tue', 'Wed', ...]
                    datasets: [{
                        label: 'Sales (Last 7 Days)',
                        data: @json($salesPerDay),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#3B82F6'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Ksh ' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Ksh ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Pie Chart - Category Distribution
            new Chart(document.getElementById('categoryPieChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: @json($categoryLabels), // From controller
                    datasets: [{
                        label: 'Category Count',
                        data: @json($categoryCounts),
                        backgroundColor: [
                            '#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#0ea5e9'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });


            // Alpine component
            function dashboardModals() {
                return {
                    openExpired() {
                        this.$refs.expiredModal.showModal();
                        fetch("{{ route('dashboard.expired-items') }}")
                            .then(res => res.text())
                            .then(html => document.getElementById('expiredItemsContent').innerHTML = html);
                    },
                    closeExpired() {
                        this.$refs.expiredModal.close();
                    },
                    openLowStock() {
                        this.$refs.lowStockModal.showModal();
                        fetch("{{ route('dashboard.low-stock-items') }}")
                            .then(res => res.text())
                            .then(html => document.getElementById('lowStockContent').innerHTML = html);
                    },
                    closeLowStock() {
                        this.$refs.lowStockModal.close();
                    },
                    openTopSelling() {
                        this.$refs.topSellingModal.showModal();
                        fetch("{{ route('dashboard.top-selling-items') }}")
                            .then(res => res.text())
                            .then(html => document.getElementById('topSellingContent').innerHTML = html);
                    },
                    closeTopSelling() {
                        this.$refs.topSellingModal.close();
                    },
                    openBottomSelling() {
                        this.$refs.bottomSellingModal.showModal();
                        fetch("{{ route('dashboard.bottom-selling-items') }}")
                            .then(res => res.text())
                            .then(html => document.getElementById('bottomSellingContent').innerHTML = html);
                    },
                    closeBottomSelling() {
                        this.$refs.bottomSellingModal.close();
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
