<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('sales.overview') }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 me-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                    Back
                </a>
                <h2 class="font-bold text-2xl">Manage Sales</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Toast container --}}
            <div id="toast" class="fixed top-6 right-6 z-50 hidden">
                <div class="bg-green-600 text-white px-4 py-2 rounded shadow">Saved successfully</div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <input id="sales-search" type="text" placeholder="Search by customer, tank, payment or status" class="border rounded px-3 py-2 w-80" />
                </div>
                <div class="flex items-center space-x-2">
                    <select id="filter-status" class="border rounded px-3 py-2 w-40 text-sm">
                        <option value="">All Statuses</option>
                        <option value="pending">🕒 Pending</option>
                        <option value="completed">✓ Completed</option>
                    </select>
                    <button type="button" data-ignore-close onclick="toggleAddSaleModal(true)" class="bg-green-600 text-white px-4 py-2 rounded">Add Sale</button>
                </div>
            </div>

            <div class="bg-white rounded shadow overflow-hidden mt-4">
                <table id="sales-table" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tank Serial Numbers</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sales as $sale)
                            @php
                                $tankSerials = $sale->tanks->pluck('serial_code')->toArray();
                                if (empty($tankSerials) && $sale->tank) {
                                    $tankSerials = [$sale->tank->serial_code];
                                }
                                $tankSerialString = implode(', ', $tankSerials);
                            @endphp
                            <tr data-customer="{{ e(optional($sale->customer)->name) }}" data-tank="{{ e($tankSerialString) }}" data-payment="{{ $sale->payment_method }}" data-status="{{ $sale->status }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">@prettyDate($sale->created_at, true)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ optional($sale->customer)->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <span class="font-semibold">{{ $sale->quantity ?? 1 }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @if(!empty($tankSerials))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($tankSerials as $serial)
                                                <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">{{ $serial }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">₱{{ number_format($sale->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($sale->status === 'completed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <button onclick="toggleEditSaleModal({{ $sale->id }})" 
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 me-1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                        Edit
                                    </button>
                                    <a href="{{ route('sales.receipt', $sale) }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 me-1.5">
                                            <path d="M6 2.25A2.25 2.25 0 003.75 4.5v3A2.25 2.25 0 006 9.75h12A2.25 2.25 0 0020.25 7.5v-3A2.25 2.25 0 0018 2.25H6zM3.75 12A2.25 2.25 0 006 14.25h12A2.25 2.25 0 0020.25 12v-.75A2.25 2.25 0 0018 9h-3.75a.75.75 0 00-.75.75v.75H10.5v-.75a.75.75 0 00-.75-.75H6A2.25 2.25 0 003.75 11.25V12z" />
                                            <path d="M6 15.75A2.25 2.25 0 003.75 18v1.5A2.25 2.25 0 006 21.75h12A2.25 2.25 0 0020.25 19.5V18A2.25 2.25 0 0018 15.75H6zm2.25 2.25h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 010-1.5z" />
                                        </svg>
                                        Print Receipt
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $sales->links() }}
            </div>
        </div>
    </div>

    @include('sales._add_sale_modal')
    @include('sales._edit_sale_modal')

    <script>
        function toggleEditSaleModal(saleId) {
            // Fetch sale data
            fetch(`/sales/${saleId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(sale => {
                    // Update form action
                    const form = document.getElementById('edit-sale-form');
                    form.action = `/sales/${saleId}`;

                    // Populate form fields
                    document.getElementById('edit-customer').value = sale.customer_id;
                    document.getElementById('edit-quantity').value = sale.quantity || 1;
                    document.getElementById('edit-price').value = sale.price;
                    document.getElementById('edit-payment').value = sale.payment_method.toLowerCase();
                    if (document.getElementById('edit-transaction-type')) {
                        document.getElementById('edit-transaction-type').value = (sale.transaction_type || 'walk_in').toLowerCase();
                    }
                    document.getElementById('edit-status').value = sale.status;

                    // Clear all checkboxes first
                    document.querySelectorAll('.edit-tank-checkbox').forEach(cb => {
                        cb.checked = false;
                        cb.disabled = false;
                    });

                    // Check tanks associated with this sale
                    if (sale.tanks && sale.tanks.length > 0) {
                        sale.tanks.forEach(tank => {
                            const checkbox = document.querySelector(`.edit-tank-checkbox[value="${tank.id}"]`);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });
                    } else if (sale.tank_id) {
                        // Fallback for backward compatibility
                        const checkbox = document.querySelector(`.edit-tank-checkbox[value="${sale.tank_id}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    }

                    // Trigger validation
                    if (typeof validateEditSelection === 'function') {
                        validateEditSelection();
                    }
                    if (typeof limitEditSelection === 'function') {
                        limitEditSelection();
                    }

                    // Show modal
                    window.dispatchEvent(new CustomEvent('open-modal', {
                        detail: 'edit-sale-modal'
                    }));
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load sale data. Please try again.');
                });
        }

        // client-side search/filter
        (function(){
            const input = document.getElementById('sales-search');
            const statusFilter = document.getElementById('filter-status');
            const table = document.getElementById('sales-table');
            if (!table) return;

            function normalize(s){ return (s||'').toString().toLowerCase(); }

            function applyFilter(){
                const q = normalize(input.value);
                const status = normalize(statusFilter.value);
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(r => {
                    const customer = normalize(r.getAttribute('data-customer'));
                    const tank = normalize(r.getAttribute('data-tank'));
                    const payment = normalize(r.getAttribute('data-payment'));
                    const st = normalize(r.getAttribute('data-status'));

                    const matchesQuery = q === '' || customer.includes(q) || tank.includes(q) || payment.includes(q) || st.includes(q);
                    const matchesStatus = status === '' || st === status;
                    if (matchesQuery && matchesStatus) r.style.display = '';
                    else r.style.display = 'none';
                });
            }

            input.addEventListener('input', applyFilter);
            statusFilter.addEventListener('change', applyFilter);

            // show toast if session contains success
            @if(session('success'))
                const toast = document.getElementById('toast');
                if (toast) {
                    toast.classList.remove('hidden');
                    setTimeout(() => { toast.classList.add('hidden'); }, 3000);
                }
            @endif
        })();

        // Handle quantity and tank selection synchronization for edit modal
        (function() {
            const quantityInput = document.getElementById('edit-quantity');
            const tankCheckboxes = document.querySelectorAll('.edit-tank-checkbox');
            const form = document.getElementById('edit-sale-form');
            const errorMsg = document.getElementById('edit-tank-selection-error');

            function updateQuantityFromSelection() {
                const selectedCount = document.querySelectorAll('.edit-tank-checkbox:checked').length;
                if (selectedCount > 0 && quantityInput) {
                    quantityInput.value = selectedCount;
                }
                validateEditSelection();
            }

            function validateEditSelection() {
                if (!quantityInput || !errorMsg) return true;
                const quantity = parseInt(quantityInput.value) || 0;
                const selectedCount = document.querySelectorAll('.edit-tank-checkbox:checked').length;
                
                if (quantity !== selectedCount && selectedCount > 0) {
                    errorMsg.style.display = 'block';
                    return false;
                } else {
                    errorMsg.style.display = 'none';
                    return true;
                }
            }

            function limitEditSelection() {
                if (!quantityInput) return;
                const quantity = parseInt(quantityInput.value) || 0;
                const checkboxes = Array.from(tankCheckboxes);
                const checked = checkboxes.filter(cb => cb.checked);
                
                if (checked.length >= quantity && quantity > 0) {
                    checkboxes.forEach(cb => {
                        if (!cb.checked) {
                            cb.disabled = true;
                        }
                    });
                } else {
                    checkboxes.forEach(cb => {
                        cb.disabled = false;
                    });
                }
            }

            // When quantity changes, limit tank selection
            if (quantityInput) {
                quantityInput.addEventListener('input', function() {
                    limitEditSelection();
                    validateEditSelection();
                });
            }

            // When tanks are selected, update quantity
            tankCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateQuantityFromSelection();
                    limitEditSelection();
                });
            });

            // Validate on form submit
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validateEditSelection()) {
                        e.preventDefault();
                        alert('Quantity must match the number of selected tanks. Please adjust your selection.');
                        return false;
                    }
                });
            }

            // Make functions globally available for toggleEditSaleModal
            window.validateEditSelection = validateEditSelection;
            window.limitEditSelection = limitEditSelection;
        })();
    </script>
</x-app-layout>
