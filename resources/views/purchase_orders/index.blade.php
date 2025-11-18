<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl">Purchase Orders</h2>
            <button type="button" data-po-new class="bg-green-600 text-white px-4 py-2 rounded">New Order</button>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-2 rounded">{{ session('success') }}</div>
            @endif
            @if(session('info'))
                <div class="bg-blue-100 border border-blue-200 text-blue-800 px-4 py-2 rounded">{{ session('info') }}</div>
            @endif

            <div class="bg-white rounded shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Brand</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">@prettyDate($order->created_at, true)</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $order->supplier?->full_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ ucfirst($order->brand) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $order->size }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $order->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">₱{{ number_format($order->unit_price,2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">₱{{ number_format($order->total_price,2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($order->status === 'received')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Received</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 flex items-center gap-2">
                                    <a href="{{ route('purchase-orders.receipt', $order) }}" target="_blank" class="px-3 py-1.5 border rounded bg-white hover:bg-gray-50">Receipt</a>
                                    @if($order->status === 'pending')
                                        <form method="POST" action="{{ route('purchase-orders.receive', $order) }}" onsubmit="return confirm('Mark this order as received?')">
                                            @csrf
                                            @method('PATCH')
                                            <button class="px-3 py-1.5 rounded bg-green-600 text-white">Mark Received</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">{{ $orders->links() }}</div>
            </div>
        </div>
    </div>

    <!-- New PO Modal -->
    <div id="po-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg w-full max-w-3xl p-6 shadow transform opacity-0 scale-95 transition-all" id="po-panel">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">New Purchase Order</h3>
                    <button onclick="togglePoModal(false)" class="text-gray-600 hover:text-gray-900">✕</button>
                </div>
                <form action="{{ route('purchase-orders.store') }}" method="POST" id="po-form" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-3">
                            <label class="text-sm font-medium">Supplier (Existing)</label>
                            <select name="supplier_id" id="supplier_id" class="w-full border rounded px-3 py-2">
                                <option value="">-- New Supplier --</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->full_name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Select an existing supplier or leave blank to add a new one below.</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium">First Name</label>
                            <input type="text" name="supplier_first_name" class="w-full border rounded px-3 py-2" />
                        </div>
                        <div>
                            <label class="text-sm font-medium">Last Name</label>
                            <input type="text" name="supplier_last_name" class="w-full border rounded px-3 py-2" />
                        </div>
                        <div>
                            <label class="text-sm font-medium">Contact Number</label>
                            <input type="text" name="supplier_contact_number" class="w-full border rounded px-3 py-2" />
                        </div>
                        <div>
                            <label class="text-sm font-medium">Email</label>
                            <input type="email" name="supplier_email" class="w-full border rounded px-3 py-2" />
                        </div>
                        <div>
                            <label class="text-sm font-medium">Contact Person</label>
                            <input type="text" name="supplier_contact_person" class="w-full border rounded px-3 py-2" />
                        </div>
                    </div>
                    <hr />
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                        <div>
                            <label class="text-sm font-medium">Brand</label>
                            <select name="brand" id="brand" class="w-full border rounded px-3 py-2 h-10" required>
                                <option value="solane">Solane</option>
                                <option value="pryce">Pryce</option>
                                <option value="petron">Petron</option>
                                <option value="phoenix">Phoenix</option>
                                <option value="petronas">Petronas</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium">Size</label>
                            <select name="size" id="size" class="w-full border rounded px-3 py-2 h-10" required>
                                <option value="50kg">50kg</option>
                                <option value="11kg">11kg</option>
                                <option value="5kg">5kg</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium">Quantity</label>
                            <input type="number" min="1" value="1" name="quantity" id="quantity" class="w-full border rounded px-3 py-2 h-10" required />
                        </div>
                        <div>
                            <label class="text-sm font-medium">Unit Price</label>
                            <input type="number" step="0.01" name="unit_price" id="unit_price" class="w-full border rounded px-3 py-2 h-10" />
                        </div>
                    </div>
                    <!-- Pricing rules helper -->
                    <div class="md:col-span-4">
                        <details class="mt-2">
                            <summary class="text-sm text-gray-600 cursor-pointer select-none flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-gray-500"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm0 3a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25ZM10.875 9a.75.75 0 0 0-.75.75v7.5c0 .414.336.75.75.75h2.25a.75.75 0 0 0 .75-.75v-7.5a.75.75 0 0 0-.75-.75h-2.25Z" clip-rule="evenodd"/></svg>
                                Pricing rules (auto-fill)
                            </summary>
                            <div class="mt-2 text-xs text-gray-600 bg-gray-50 border border-gray-200 rounded p-3">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>11kg: Solane/Pryce/Petron = ₱1,150 each</li>
                                    <li>11kg: Petronas/Phoenix = ₱1,100 each</li>
                                    <li>2.7kg: Pryce = ₱380 each</li>
                                    <li>50kg: Petronas/Phoenix = ₱3,900 each</li>
                                    <li>Other combos: enter unit price manually</li>
                                </ul>
                            </div>
                        </details>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">Total: <span id="total_display" class="font-semibold">₱0.00</span></div>
                        <div class="space-x-2">
                            <button type="button" onclick="togglePoModal(false)" class="px-4 py-2 rounded border">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white">Save Order</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePoModal(show) {
            const modal = document.getElementById('po-modal');
            const panel = document.getElementById('po-panel');
            if (!modal || !panel) return;
            if (show) {
                modal.classList.remove('hidden');
                requestAnimationFrame(()=>{
                    panel.classList.remove('opacity-0','scale-95');
                    panel.classList.add('opacity-100','scale-100');
                });
            } else {
                panel.classList.remove('opacity-100','scale-100');
                panel.classList.add('opacity-0','scale-95');
                setTimeout(()=> modal.classList.add('hidden'), 180);
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            const newBtn = document.querySelector('[data-po-new]');
            if (newBtn) {
                newBtn.addEventListener('click', e => {
                    e.preventDefault();
                    e.stopPropagation();
                    togglePoModal(true);
                });
            }
        });
        document.addEventListener('click', e => {
            const modal = document.getElementById('po-modal');
            if (!modal || modal.classList.contains('hidden')) return;
            const panel = document.getElementById('po-panel');
            if (panel && !panel.contains(e.target) && !e.target.closest('[data-ignore-close]')) {
                togglePoModal(false);
            }
        });
        (function(){
            const priceMap = @json($priceMap);
            const brandSel = document.getElementById('brand');
            const sizeSel = document.getElementById('size');
            const qty = document.getElementById('quantity');
            const unit = document.getElementById('unit_price');
            const hint = document.getElementById('unitPriceHint');
            const totalDisplay = document.getElementById('total_display');
            const form = document.getElementById('po-form');
            function is50kg(v){ v=(v||'').toLowerCase(); return v==='50kg'||v==='50 kg'||v==='50'||v.indexOf('50')!==-1; }
            function normalizeSize(v){ v=(v||'').toLowerCase(); if(is50kg(v)) return '50kg'; if(v==='11kg'||v==='11 kg'||v.indexOf('11')!==-1) return '11kg'; if(v==='2.7kg'||v==='2.7 kg'||v.indexOf('2.7')!==-1) return '2.7kg'; return null; }
            function normalizeBrand(b){ b=(b||'').toLowerCase(); return b; }
            function recalc(){
                const sizeKey = normalizeSize(sizeSel.value);
                const brand = normalizeBrand(brandSel.value);
                const q = parseInt(qty.value)||0;
                if (sizeKey && priceMap[sizeKey] && priceMap[sizeKey][brand] != null){
                    const perUnit = parseFloat(priceMap[sizeKey][brand]);
                    unit.dataset.perUnit = perUnit.toFixed(2);
                    unit.value = (perUnit * q).toFixed(2); // show aggregated price for all units
                    unit.dataset.autofilled = 'true';
                    hint.classList.remove('invisible');
                } else {
                    hint.classList.add('invisible');
                    if (unit.dataset.autofilled === 'true') {
                        unit.value = '';
                        unit.dataset.perUnit = '';
                        unit.dataset.autofilled = 'false';
                    }
                }
                // Total equals displayed (aggregated) price when autofilled; otherwise manual unit * qty
                if (unit.dataset.autofilled === 'true') {
                    totalDisplay.textContent = '₱'+ (parseFloat(unit.value)||0).toFixed(2);
                } else {
                    const up = parseFloat(unit.value)||0;
                    totalDisplay.textContent = '₱'+ (q*up).toFixed(2);
                }
            }
            [brandSel,sizeSel,qty,unit].forEach(el=> {
                el.addEventListener('input', recalc);
                el.addEventListener('change', recalc);
            });
            unit.addEventListener('input', ()=>{ unit.dataset.autofilled = 'false'; unit.dataset.perUnit=''; recalc(); });
            // Before submit, if autofilled & aggregated, revert unit_price to per-unit for server calculation
            if (form) {
                form.addEventListener('submit', () => {
                    if (unit.dataset.autofilled === 'true') {
                        const perUnit = unit.dataset.perUnit;
                        if (perUnit) {
                            unit.value = perUnit; // send per-unit price to server
                        }
                    }
                });
            }
            recalc();
        })();
    </script>
</x-app-layout>
