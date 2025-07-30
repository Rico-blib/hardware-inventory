<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Add New Sale</h2>
    </x-slot>

    <div class="py-4 px-6">
        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('sales.store') }}" method="POST">
            @csrf

            <!-- Customer Selection -->
            <div class="mb-4">
                <label for="customer_id" class="block font-medium">Customer</label>
                <select name="customer_id" id="customer_id" class="w-full border px-2 py-1 rounded">
                    <option value="">Walk-in</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sale Items Table -->
            <div class="overflow-x-auto">
                <table class="w-full bg-white shadow rounded" id="sale-items-table">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-4 py-2">Product</th>
                            <th class="px-4 py-2">Qty</th>
                            <th class="px-4 py-2">Price</th>
                            <th class="px-4 py-2">Subtotal</th>
                            <th class="px-4 py-2">
                                <button type="button" id="add-row"
                                    class="bg-green-700 hover:bg-green-800 text-white px-2 py-1 rounded text-sm">+</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-2 py-1">
                                <select name="items[0][product_id]"
                                    class="w-full border px-2 py-1 rounded product-select">
                                    <option value="">Select</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-2 py-1">
                                <input type="number" name="items[0][quantity]"
                                    class="quantity w-full border px-2 py-1 rounded" min="1" value="1">
                            </td>
                            <td class="px-2 py-1">
                                <input type="number" name="items[0][price]"
                                    class="price w-full border px-2 py-1 rounded bg-gray-100" min="0"
                                    step="0.01" value="0.00" readonly>
                            </td>
                            <td class="px-2 py-1">
                                <input type="text" class="subtotal w-full border px-2 py-1 rounded bg-gray-100"
                                    readonly value="0.00">
                            </td>
                            <td class="px-2 py-1 text-center">
                                <button type="button"
                                    class="remove-row bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-sm">X</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Discount -->
            <div class="mt-4">
                <label for="discount" class="block font-medium">Discount (optional)</label>
                <input type="number" name="discount" id="discount" class="w-full border px-2 py-1 rounded"
                    min="0" step="0.01" value="0.00">
            </div>

            <!-- Total -->
            <div class="mt-4">
                <label class="block font-medium">Total</label>
                <input type="text" id="total" class="w-full border px-2 py-1 rounded bg-gray-100" readonly
                    value="0.00">
            </div>
            <div class="mb-4">
                <label for="payment_method" class="block font-medium text-sm text-gray-700">Payment Method</label>
                <select name="payment_method" id="payment_method" required
                    class="form-select rounded-md shadow-sm mt-1 block w-full">
                    <option value="">-- Select Payment Method --</option>
                    <option value="cash">Cash</option>
                    <option value="mpesa">M-PESA</option>
                    <option value="bank">Bank Transfer</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="mt-6">
                <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded">Save
                    Sale</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @push('scripts')
        <script>
            let rowCount = 1;

            function calculateTotals() {
                let total = 0;
                document.querySelectorAll('#sale-items-table tbody tr').forEach(row => {
                    const qty = parseFloat(row.querySelector('.quantity')?.value || 0);
                    const price = parseFloat(row.querySelector('.price')?.value || 0);
                    const subtotal = qty * price;
                    row.querySelector('.subtotal').value = subtotal.toFixed(2);
                    total += subtotal;
                });

                const discount = parseFloat(document.getElementById('discount')?.value || 0);
                total -= discount;
                document.getElementById('total').value = total.toFixed(2);
            }

            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity') || e.target.classList.contains('price') || e.target.id ===
                    'discount') {
                    calculateTotals();
                }
            });

            document.getElementById('add-row').addEventListener('click', function() {
                const tbody = document.querySelector('#sale-items-table tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                <td class="px-2 py-1">
                    <select name="items[${rowCount}][product_id]" class="w-full border px-2 py-1 rounded product-select">
                        <option value="">Select</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="px-2 py-1">
                    <input type="number" name="items[${rowCount}][quantity]" class="quantity w-full border px-2 py-1 rounded" min="1" value="1">
                </td>
                <td class="px-2 py-1">
                    <input type="number" name="items[${rowCount}][price]" class="price w-full border px-2 py-1 rounded bg-gray-100" min="0" step="0.01" value="0.00" readonly>
                </td>
                <td class="px-2 py-1">
                    <input type="text" class="subtotal w-full border px-2 py-1 rounded bg-gray-100" readonly value="0.00">
                </td>
                <td class="px-2 py-1 text-center">
                    <button type="button" class="remove-row bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-sm">X</button>
                </td>`;
                tbody.appendChild(newRow);
                rowCount++;
                calculateTotals();
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.closest('tr').remove();
                    calculateTotals();
                }
            });

            // Fetch product price
            $(document).on('change', '.product-select', function() {
                const row = $(this).closest('tr');
                const productId = $(this).val();
                const priceInput = row.find('.price');

                if (productId) {
                    $.get(`/products/${productId}/price`, function(data) {
                        priceInput.val(parseFloat(data.price).toFixed(2));
                        calculateTotals();
                    }).fail(function() {
                        console.error('Failed to fetch product price');
                    });
                } else {
                    priceInput.val('0.00');
                    calculateTotals();
                }
            });

            calculateTotals();
        </script>
    @endpush
</x-app-layout>
