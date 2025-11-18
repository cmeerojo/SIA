<div id="add-sale-modal" class="fixed inset-0 z-50 hidden">
    @php
        // Ensure the partial is resilient: if the parent view didn't pass `$availableTanks`,
        // fall back to `$tanks` or query filled tanks directly.
        if (!isset($availableTanks)) {
            $availableTanks = $tanks ?? \App\Models\Tank::where('status', 'filled')->orderBy('serial_code')->get();
        }
    @endphp
    <div id="add-sale-backdrop" class="absolute inset-0 bg-black bg-opacity-40 transition-opacity"></div>

    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div id="add-sale-panel" class="bg-white rounded-lg w-full max-w-2xl p-6 transform opacity-0 scale-95 transition-all">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Add Sale</h3>
                <button type="button" onclick="toggleAddSaleModal(false)" class="text-gray-600 hover:text-gray-900">✕</button>
            </div>

            @if($errors->any())
                <div class="mb-4 text-red-600">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sales.store') }}" method="POST" id="add-sale-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm">Customer</label>
                        <select name="customer_id" id="customer-select" required class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-orange-200">
                            <option value="">Select customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" data-type="{{ strtolower($customer->type ?? 'customer') }}">{{ $customer->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm">Quantity</label>
                        <input type="number" name="quantity" id="quantity-input" min="1" value="1" required class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-orange-200" />
                        <p class="text-xs text-gray-500 mt-1">Number of tanks to sell</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm">Select Tanks (Serial Numbers)</label>
                        <div id="tank-selection-container" class="border rounded p-3 max-h-48 overflow-y-auto">
                            <p class="text-xs text-gray-500 mb-2">Select tanks by checking the boxes below. Quantity must match number of selected tanks.</p>
                            <div class="space-y-2" id="tank-checkboxes">
                                @foreach($availableTanks as $tank)
                                    <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                        <input type="checkbox" name="tank_ids[]" value="{{ $tank->id }}" data-brand="{{ strtolower($tank->brand ?? '') }}" data-size="{{ strtolower($tank->size ?? '') }}" class="tank-checkbox border-gray-300 rounded text-orange-600 focus:ring-orange-500" />
                                        <span class="text-sm">{{ $tank->serial_code }} - {{ $tank->brand ?? 'N/A' }} ({{ $tank->size ?? 'N/A' }})</span>
                                    </label>
                                @endforeach
                            </div>
                            @if(empty($availableTanks) || $availableTanks->isEmpty())
                                <p class="text-sm text-gray-500">No available tanks (status: filled)</p>
                            @endif
                        </div>
                        <p class="text-xs text-red-500 mt-1" id="tank-selection-error" style="display: none;">Quantity must match the number of selected tanks.</p>
                    </div>

                    <div>
                        <label class="text-sm">Price</label>
                        <input id="price-input" type="number" step="0.01" name="price" required class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-orange-200" />
                        <p id="auto-price-hint" class="text-xs text-gray-500 mt-1" style="display:none;">Auto-calculated from size/brand pricing.</p>
                        <p id="price-zero-error" class="text-xs text-red-500 mt-1" style="display:none;">Price must be greater than zero.</p>
                    </div>

                    <div>
                        <label class="text-sm">Payment Method</label>
                        <select name="payment_method" required class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-orange-200">
                            <option value="cash">Cash</option>
                            <option value="gcash">G-cash</option>
                            <option value="credit_card">Credit Card</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm">Transaction Type</label>
                        <select name="transaction_type" required class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-orange-200">
                            <option value="walk_in">Walk-in</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm">Status</label>
                        <select name="status" required class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-orange-200">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" onclick="toggleAddSaleModal(false)" class="px-4 py-2 rounded border">Cancel</button>
                    <button id="add-sale-submit" type="submit" class="px-4 py-2 rounded bg-gradient-to-r from-green-500 to-green-600 text-white shadow disabled:opacity-50 disabled:cursor-not-allowed">Save Sale</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleAddSaleModal(show) {
        const container = document.getElementById('add-sale-modal');
        const panel = document.getElementById('add-sale-panel');
        if (show) {
            container.classList.remove('hidden');
            // small delay to allow transition
            setTimeout(() => {
                panel.classList.remove('opacity-0', 'scale-95');
                panel.classList.add('opacity-100', 'scale-100');
            }, 10);
            // attach key handler
            document.addEventListener('keydown', escHandler);
        } else {
            panel.classList.remove('opacity-100', 'scale-100');
            panel.classList.add('opacity-0', 'scale-95');
            // wait for animation then hide
            setTimeout(() => {
                container.classList.add('hidden');
            }, 200);
            document.removeEventListener('keydown', escHandler);
        }
    }

    function escHandler(e) {
        if (e.key === 'Escape') toggleAddSaleModal(false);
    }

    // click outside to close
    document.addEventListener('click', function (e) {
        const container = document.getElementById('add-sale-modal');
        if (!container || container.classList.contains('hidden')) return;
        const panel = document.getElementById('add-sale-panel');
        if (panel && !panel.contains(e.target) && !e.target.closest('[data-ignore-close]')) {
            toggleAddSaleModal(false);
        }
    });

    // Handle quantity and tank selection synchronization
    (function() {
        const quantityInput = document.getElementById('quantity-input');
        const tankCheckboxes = document.querySelectorAll('.tank-checkbox');
        const priceInput = document.getElementById('price-input');
        const autoHint = document.getElementById('auto-price-hint');

        const priceMap = {
            '11kg': { solane: 1150, pryce: 1150, petron: 1150, petronas: 1100, phoenix: 1100 },
            '2.7kg': { pryce: 380 },
            '50kg': { phoenix: 3900, petronas: 3900 }
        };
        const form = document.getElementById('add-sale-form');
        const errorMsg = document.getElementById('tank-selection-error');
        const customerSel = document.getElementById('customer-select');

        function updateQuantityFromSelection() {
            const selectedCount = document.querySelectorAll('.tank-checkbox:checked').length;
            if (selectedCount > 0) {
                quantityInput.value = selectedCount;
            }
            validateSelection();
            autoPrice();
        }

        function validateSelection() {
            const quantity = parseInt(quantityInput.value) || 0;
            const selectedCount = document.querySelectorAll('.tank-checkbox:checked').length;
            
            if (quantity !== selectedCount && selectedCount > 0) {
                errorMsg.style.display = 'block';
                return false;
            } else {
                errorMsg.style.display = 'none';
                return true;
            }
        }

        function limitSelection() {
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

        function is50kg(size) {
            if (!size) return false;
            size = ('' + size).toLowerCase();
            return size === '50kg' || size === '50 kg' || size === '50' || size.indexOf('50') !== -1;
        }

        function normalizeSize(size){
            size = (''+ (size||'')).toLowerCase();
            if (is50kg(size)) return '50kg';
            if (size === '11kg' || size === '11 kg' || size.indexOf('11') !== -1) return '11kg';
            if (size === '2.7kg' || size === '2.7 kg' || size.indexOf('2.7') !== -1) return '2.7kg';
            return null;
        }
        function normalizeBrand(brand){
            brand = (''+(brand||'')).toLowerCase();
            if (brand === 'petronas') return 'petronas';
            if (brand === 'phoenix') return 'phoenix';
            if (brand === 'petron') return 'petron';
            if (brand === 'solane') return 'solane';
            if (brand === 'pryce') return 'pryce';
            return brand;
        }

        function autoPrice() {
            if (!priceInput) return;
            const checked = Array.from(document.querySelectorAll('.tank-checkbox:checked'));
            let total = 0; let used = true;
            for (const cb of checked) {
                const brand = normalizeBrand(cb.getAttribute('data-brand'));
                const sizeKey = normalizeSize(cb.getAttribute('data-size'));
                if (sizeKey && priceMap[sizeKey] && priceMap[sizeKey][brand] != null){
                    total += Number(priceMap[sizeKey][brand]);
                } else {
                    used = false; break;
                }
            }
            if (used) {
                priceInput.value = total.toFixed(2);
                if (autoHint) autoHint.style.display = '';
            } else {
                if (autoHint) autoHint.style.display = 'none';
            }
            updateSubmitDisabled();
        }

        function enforceBusinessFor50kg() {
            const customerType = (customerSel.options[customerSel.selectedIndex]?.dataset.type || 'customer').toLowerCase();
            const has50 = Array.from(document.querySelectorAll('.tank-checkbox:checked')).some(cb => is50kg(cb.getAttribute('data-size')));
            if (has50 && customerType !== 'business') {
                alert('50kg tanks can only be sold to business customers.');
                return false;
            }
            return true;
        }

        function updateTankVisibility() {
            const type = (customerSel.options[customerSel.selectedIndex]?.dataset.type || 'customer').toLowerCase();
            const hide50 = type !== 'business';
            const items = Array.from(document.querySelectorAll('#tank-checkboxes label'));
            items.forEach(lbl => {
                const cb = lbl.querySelector('.tank-checkbox');
                if (!cb) return;
                const isBig = is50kg(cb.getAttribute('data-size'));
                if (hide50 && isBig) {
                    cb.checked = false;
                    lbl.style.display = 'none';
                } else {
                    lbl.style.display = '';
                }
            });
            // Re-run constraints after visibility change
            updateQuantityFromSelection();
            limitSelection();
            autoPrice();
        }

        // When quantity changes, limit tank selection
        quantityInput.addEventListener('input', function() {
            limitSelection();
            validateSelection();
            autoPrice();
        });

        // When tanks are selected, update quantity
        tankCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateQuantityFromSelection();
                limitSelection();
                autoPrice();
                enforceBusinessFor50kg();
            });
        });

        // Disable submit when price is zero
        const submitBtn = document.getElementById('add-sale-submit');
        const zeroErr = document.getElementById('price-zero-error');
        function updateSubmitDisabled(){
            const val = parseFloat(priceInput.value || '0');
            if (!isFinite(val) || val <= 0){
                submitBtn.disabled = true;
                if (zeroErr) zeroErr.style.display = '';
            } else {
                submitBtn.disabled = false;
                if (zeroErr) zeroErr.style.display = 'none';
            }
        }
        priceInput.addEventListener('input', updateSubmitDisabled);

        // Validate on form submit
        form.addEventListener('submit', function(e) {
            if (!validateSelection()) {
                e.preventDefault();
                alert('Quantity must match the number of selected tanks. Please adjust your selection.');
                return false;
            }
            if (!enforceBusinessFor50kg()) {
                e.preventDefault();
                return false;
            }
            updateSubmitDisabled();
            if (submitBtn.disabled){
                e.preventDefault();
                alert('Cannot save sale with price 0.');
                return false;
            }
        });

        // Initialize
        limitSelection();
        autoPrice();
        updateSubmitDisabled();
        customerSel.addEventListener('change', () => {
            updateTankVisibility();
            enforceBusinessFor50kg();
            autoPrice();
        });

        // Initial visibility pass (in case a customer is preselected)
        updateTankVisibility();
    })();
</script>
