<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            📦 Purchases Report
        </h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow space-y-6">
        <!-- Flash Message -->
        @if (session('success'))
            <div class="px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Top Actions -->
        <div class="flex justify-between items-center mb-4">
            <!-- Generate Report Button -->
            <button onclick="document.getElementById('dateModal').showModal()"
                class="bg-orange-700 text-white px-4 py-2 rounded hover:bg-orange-800">
                📅 Generate Report
            </button>

            <!-- Export Dropdown -->
            <div class="relative">
                <button onclick="toggleExport()" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">
                    ⬇️ Export Data
                </button>
                <ul id="exportMenu" class="absolute right-0 mt-2 w-40 bg-white border rounded shadow hidden z-10">
                    <li><a href="{{ route('reports.purchases.export', ['type' => 'pdf']) }}"
                            class="block px-4 py-2 hover:bg-gray-100">PDF</a></li>
                    <li><a href="{{ route('reports.purchases.export', ['type' => 'excel']) }}"
                            class="block px-4 py-2 hover:bg-gray-100">Excel</a></li>
                    <li><a href="{{ route('reports.purchases.export', ['type' => 'csv']) }}"
                            class="block px-4 py-2 hover:bg-gray-100">CSV</a></li>
                    <li><a href="#" onclick="window.print()" class="block px-4 py-2 hover:bg-gray-100">🖨️
                            Print</a></li>
                </ul>
            </div>

            <!-- Search Input -->
            <div>
                <input type="text" id="searchInput" onkeyup="filterTable()"
                    class="border px-3 py-2 rounded shadow-sm" placeholder="🔍 Search...">
            </div>
        </div>

        <!-- Purchases Table -->
        @if (request()->filled('from') && request()->filled('to'))
            <div class="overflow-x-auto mt-6">
                <table class="w-full border bg-white shadow rounded" id="purchasesTable">
                    <thead class="bg-gray-800 text-white text-sm">
                        <tr>
                            <th class="px-4 py-2">Invoice </th>
                            <th class="px-4 py-2">Supplier</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Total</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($purchases) > 0)
                            @foreach ($purchases as $purchase)
                                <tr class="border-t text-sm">
                                    <td class="px-4 py-2">{{ $purchase->invoice_no }}</td>
                                    <td class="px-4 py-2">{{ $purchase->supplier->name ?? 'Unknown' }}</td>
                                    <td class="px-4 py-2">{{ $purchase->created_at->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2">Ksh {{ number_format($purchase->total, 2) }}</td>
                                     <td class="px-4 py-2">{{ $purchase->payment_status }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center text-gray-500 py-4">
                                    No purchases found for selected dates.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Date Range Modal -->
    <dialog id="dateModal" class="rounded-lg shadow-lg p-6">
        <form method="GET" action="{{ route('reports.purchases') }}">
            <h3 class="text-lg font-semibold mb-4">Select Date Range</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="from">From:</label>
                    <input type="date" name="from" required class="w-full border px-3 py-2 rounded">
                </div>
                <div>
                    <label for="to">To:</label>
                    <input type="date" name="to" required class="w-full border px-3 py-2 rounded">
                </div>
            </div>
            <div class="flex justify-between gap-2">
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded">Apply</button>
                <a href="{{ route('reports.purchases') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Reset</a>
                <button type="button" onclick="document.getElementById('dateModal').close()"
                    class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
            </div>
        </form>
    </dialog>

    <!-- Scripts -->
    @push('scripts')
        <script>
            function toggleExport() {
                const menu = document.getElementById('exportMenu');
                menu.classList.toggle('hidden');
            }

            function filterTable() {
                const input = document.getElementById('searchInput');
                const filter = input.value.toLowerCase();
                const rows = document.querySelectorAll('#purchasesTable tbody tr');

                rows.forEach(row => {
                    const rowText = row.innerText.toLowerCase();
                    row.style.display = rowText.includes(filter) ? '' : 'none';
                });
            }
        </script>
    @endpush

</x-app-layout>
