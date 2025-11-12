<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">Items List</h2>
            <span class="text-gray-500 text-base font-normal">Manage your inventory with a clear, professional overview of all products.</span>
        </div>
    </x-slot>
    <div class="py-12 bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 shadow-lg sm:rounded-2xl overflow-hidden">
                <div class="p-8 text-gray-900">
                    <div class="flex flex-wrap gap-6 mb-10">
                        <!-- Dashboard nodes (already formalized) -->
                        @php $cardClass = 'flex-1 min-w-[220px] bg-white border border-gray-200 shadow-md rounded-lg p-6 flex flex-col items-center'; @endphp
                        <div class="{{ $cardClass }}">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path>
                                </svg>
                                <span class="font-semibold text-lg text-gray-700">Total Products</span>
                            </div>
                            <div class="text-3xl font-bold text-blue-700">{{ $totalItems }}</div>
                        </div>
                        <div class="{{ $cardClass }}">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 10c-4.418 0-8-1.79-8-4V6a2 2 0 012-2h12a2 2 0 012 2v8c0 2.21-3.582 4-8 4z"></path></svg>
                                <span class="font-semibold text-lg text-gray-700">Most Recent Product</span>
                            </div>
                            @if($mostRecentItem)
                                <div class="text-base text-gray-800 font-medium">{{ $mostRecentItem->brand }}</div>
                                <div class="text-sm text-gray-500">Size: {{ $mostRecentItem->size }} | Amount: {{ $mostRecentItem->amount }}</div>
                                <div class="text-xs text-gray-400 mt-1">Added: {{ $mostRecentItem->created_at->format('M d, Y H:i') }}</div>
                            @else
                                <div class="text-gray-500">No products yet.</div>
                            @endif
                        </div>
                        <div class="{{ $cardClass }} justify-between">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="font-semibold text-lg text-gray-700">Product Add Dates</span>
                            </div>
                            <button onclick="document.getElementById('product-date-modal').classList.remove('hidden')" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded shadow transition">Show List</button>
                        </div>
                    </div>
                    <!-- Modal for product add dates -->
                    <div id="product-date-modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                        <div class="bg-white rounded-xl shadow-2xl p-0 max-w-2xl w-full relative border border-gray-200">
                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 rounded-t-xl bg-gray-50">
                                <h3 class="text-xl font-bold text-gray-800">Product Add Dates</h3>
                                <button onclick="document.getElementById('product-date-modal').classList.add('hidden')" class="text-2xl text-gray-400 hover:text-gray-700 font-bold focus:outline-none">&times;</button>
                            </div>
                            <div class="overflow-x-auto max-h-96 px-6 py-4">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Brand</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Size</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Added Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach($itemsWithDates as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->brand }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->size }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->amount }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 rounded-b-xl flex justify-end">
                                <button onclick="document.getElementById('product-date-modal').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded shadow">Close</button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-6 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <button onclick="openAddEntityModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded shadow transition">Add Item</button>
                            <button onclick="document.getElementById('stock-movements-modal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded shadow transition">Stock Movements</button>
                            <button onclick="document.getElementById('stock-movements-modal').classList.remove('hidden')" class="ml-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded shadow transition">Manage Tanks</button>
                        </div>
                    </div>
                    <!-- Tanks quick panel -->
                    <div class="mb-6">
                        <div class="bg-white border rounded p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="font-semibold">Tanks</h3>
                                    <div class="text-sm text-gray-500">In store: {{ $tanksInStore }} • With customers: {{ $tanksWithCustomers }}</div>
                                </div>
                                <div>
                                    <button onclick="document.getElementById('stock-movements-modal').classList.remove('hidden')" class="text-sm text-indigo-600">Manage tanks</button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach($recentTanks as $t)
                                    <div class="border rounded p-2 text-sm text-gray-700">
                                        <div class="font-medium">{{ $t->serial_code }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst($t->status) }}</div>
                                        <div class="text-xs text-gray-400">Added: {{ $t->created_at->format('Y-m-d') }}</div>
                                        <div class="mt-2 text-right"><a href="{{ route('tanks.show', $t) }}" class="text-indigo-600 text-xs">History</a></div>
                                    </div>
                                @endforeach
                                @if($recentTanks->isEmpty())
                                    <div class="text-gray-500">No tanks yet</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Brand</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Size</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Valve Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($groups as $g)
                                    @php
                                        $key = ($g->brand ?? '') . '||' . ($g->size ?? '') . '||' . ($g->valve_type ?? '');
                                        $list = $serials->get($key) ?? collect();
                                    @endphp
                                    <tr class="group-row" data-key="{{ e($key) }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $g->brand }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $g->size }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $g->valve_type ? ($g->valve_type === 'POL' ? 'POL valve' : ($g->valve_type === 'A/S' ? 'A/S valve' : $g->valve_type)) : '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $g->total_amount }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2 items-center">
                                            <button type="button" class="toggle-serials bg-indigo-100 text-indigo-700 py-1 px-3 rounded text-xs" data-key="{{ e($key) }}">Show serials ({{ $g->serial_count }})</button>
                                            <button type="button" onclick="document.getElementById('stock-movements-modal').classList.remove('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-1 px-3 rounded text-xs">Manage</button>
                                        </td>
                                    </tr>
                                    <tr class="serials-row hidden bg-gray-50">
                                        <td colspan="5" class="px-6 py-3 text-sm text-gray-700">
                                            @if($list->isEmpty())
                                                <div class="text-gray-500">No serial-coded tanks for this group.</div>
                                            @else
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($list as $s)
                                                        <div class="border rounded px-3 py-1 text-xs bg-white">{{ $s->serial_code }} <span class="text-gray-400">({{ $s->status }})</span></div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Tank Edit / Stock Movements (repurposed) Modal -->
                    <div id="stock-movements-modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-start justify-center z-50 pt-16 overflow-auto">
                        <div class="bg-white rounded-xl shadow-2xl p-0 max-w-4xl w-full relative border border-gray-200">
                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 rounded-t-xl bg-gray-50">
                                <h3 class="text-xl font-bold text-gray-800">Tanks (Edit / Movements)</h3>
                                <button onclick="document.getElementById('stock-movements-modal').classList.add('hidden')" class="text-2xl text-gray-400 hover:text-gray-700 font-bold focus:outline-none">&times;</button>
                            </div>
                            <div class="px-6 py-4">
                                <div class="overflow-x-auto max-h-96">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Serial</th>
                                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Brand</th>
                                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Valve</th>
                                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Added</th>
                                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                                                </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach($recentTanks as $t)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $t->serial_code }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $t->brand ?? '—' }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">{{ $t->valve_type ?? '—' }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">{{ ucfirst($t->status) }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">{{ $t->created_at->format('Y-m-d') }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">
                                                    <button class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-1 px-3 rounded shadow open-edit-tank" data-id="{{ $t->id }}" data-serial="{{ $t->serial_code }}" data-status="{{ $t->status }}" data-brand="{{ $t->brand }}" data-valve="{{ $t->valve_type }}">Edit</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 rounded-b-xl flex justify-end">
                                <button onclick="document.getElementById('stock-movements-modal').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded shadow">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Combined Add Item / Tank Modal -->
<div id="add-entity-modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-2xl w-full border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold">Add Item or Tank</h3>
            <button onclick="document.getElementById('add-entity-modal').classList.add('hidden')" class="text-2xl text-gray-400">&times;</button>
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center mr-4">
                <input type="radio" name="entity_type" value="item" checked class="entity-toggle"> <span class="ms-2">Item</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" name="entity_type" value="tank" class="entity-toggle"> <span class="ms-2">Tank</span>
            </label>
        </div>

        <!-- Item form -->
        <form id="add-item-form" action="{{ route('items.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="text-sm font-medium">Brand</label>
                    <input type="text" name="brand" class="w-full mt-1 border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="text-sm font-medium">Size</label>
                    <select name="size" class="w-full mt-1 border rounded px-3 py-2" required>
                        <option value="">Select size</option>
                        <option value="S">Small (S)</option>
                        <option value="M">Medium (M)</option>
                        <option value="L">Large (L)</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Amount</label>
                    <input type="number" name="amount" min="0" class="w-full mt-1 border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="text-sm font-medium">Valve Type</label>
                    <select name="valve_type" class="w-full mt-1 border rounded px-3 py-2">
                        <option value="">Select valve type</option>
                        <option value="POL">POL valve</option>
                        <option value="A/S">A/S valve</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('add-entity-modal').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">Add Item</button>
            </div>
        </form>

        <!-- Tank form (hidden by default) -->
        <form id="add-tank-form" action="{{ route('tanks.store') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="from_items" value="1">
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="text-sm font-medium">Serial Code</label>
                    <input type="text" name="serial_code" class="w-full mt-1 border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="text-sm font-medium">Brand</label>
                    <input type="text" name="brand" class="w-full mt-1 border rounded px-3 py-2">
                </div>
                <div>
                    <label class="text-sm font-medium">Size</label>
                    <select name="size" class="w-full mt-1 border rounded px-3 py-2" required>
                        <option value="">Select size</option>
                        <option value="S">Small (S)</option>
                        <option value="M">Medium (M)</option>
                        <option value="L">Large (L)</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Valve Type</label>
                    <select name="valve_type" class="w-full mt-1 border rounded px-3 py-2">
                        <option value="">Select valve type</option>
                        <option value="POL">POL</option>
                        <option value="A/S">A/S</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Status</label>
                    <select name="status" class="w-full mt-1 border rounded px-3 py-2">
                        <option value="filled">Filled</option>
                        <option value="empty">Empty</option>
                        <option value="with_customer">With customer</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('add-entity-modal').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">Add Tank</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Tank Modal (used from Tanks panel / Stock Movements repurposed) -->
<div id="edit-tank-modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-lg w-full border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold">Edit Tank</h3>
            <button onclick="document.getElementById('edit-tank-modal').classList.add('hidden')" class="text-2xl text-gray-400">&times;</button>
        </div>

        <form id="edit-tank-form" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="from_items" value="1">
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="text-sm font-medium">Serial Code</label>
                    <input type="text" name="serial_code" id="edit-serial_code" class="w-full mt-1 border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="text-sm font-medium">Brand</label>
                    <input type="text" name="brand" id="edit-brand" class="w-full mt-1 border rounded px-3 py-2">
                </div>
                <div>
                    <label class="text-sm font-medium">Valve Type</label>
                    <select name="valve_type" id="edit-valve_type" class="w-full mt-1 border rounded px-3 py-2">
                        <option value="">Select valve type</option>
                        <option value="POL">POL</option>
                        <option value="A/S">A/S</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Status</label>
                    <select name="status" id="edit-status" class="w-full mt-1 border rounded px-3 py-2">
                        <option value="filled">Filled</option>
                        <option value="empty">Empty</option>
                        <option value="with_customer">With customer</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('edit-tank-modal').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Open the combined Add Item/Tank modal and reset to Item by default
    function openAddEntityModal() {
        // set to 'item'
        const itemRadio = document.querySelector('input[name="entity_type"][value="item"]');
        const tankRadio = document.querySelector('input[name="entity_type"][value="tank"]');
        if (itemRadio) itemRadio.checked = true;
        if (tankRadio) tankRadio.checked = false;

        // show item form, hide tank form
        document.getElementById('add-item-form').classList.remove('hidden');
        document.getElementById('add-tank-form').classList.add('hidden');

        // clear inputs
        document.getElementById('add-item-form').reset();
        document.getElementById('add-tank-form').reset();

        document.getElementById('add-entity-modal').classList.remove('hidden');
    }

    // Toggle between Item and Tank forms in the combined Add modal
    document.querySelectorAll('.entity-toggle').forEach(function(r) {
        r.addEventListener('change', function() {
            const type = this.value;
            document.getElementById('add-item-form').classList.toggle('hidden', type !== 'item');
            document.getElementById('add-tank-form').classList.toggle('hidden', type !== 'tank');
        });
    });

    // Open Edit Tank modal and populate form
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('open-edit-tank')) {
            const btn = e.target;
            const id = btn.getAttribute('data-id');
            const serial = btn.getAttribute('data-serial');
            const status = btn.getAttribute('data-status');
            const brand = btn.getAttribute('data-brand');
            const valve = btn.getAttribute('data-valve');

            document.getElementById('edit-serial_code').value = serial;
            document.getElementById('edit-status').value = status;
            document.getElementById('edit-brand').value = brand || '';
            document.getElementById('edit-valve_type').value = valve || '';

            // set form action to the tank update route
            const form = document.getElementById('edit-tank-form');
            form.action = '/tanks/' + id;

            document.getElementById('edit-tank-modal').classList.remove('hidden');
        }
    });

    // Toggle serials list for a grouped row
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('toggle-serials')) {
            const btn = e.target;
            // find the next .serials-row after the clicked button's row
            const tr = btn.closest('tr');
            if (!tr) return;
            const next = tr.nextElementSibling;
            if (!next) return;
            next.classList.toggle('hidden');
            btn.textContent = next.classList.contains('hidden') ? `Show serials (${btn.textContent.match(/\d+/) ? btn.textContent.match(/\d+/)[0] : ''})` : 'Hide serials';
        }
    });
</script>
