<div id="add-sale-modal" class="fixed inset-0 z-50 hidden">
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

            <form action="{{ route('sales.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm">Customer</label>
                        <select name="customer_id" required class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-orange-200">
                            <option value="">Select customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm">Tank</label>
                        <select name="tank_id" required class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-orange-200">
                            <option value="">Select tank</option>
                            @foreach($tanks as $tank)
                                <option value="{{ $tank->id }}">{{ $tank->serial_code }} ({{ $tank->brand }} - {{ $tank->size }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm">Price</label>
                        <input type="number" step="0.01" name="price" required class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-orange-200" />
                    </div>

                    <div>
                        <label class="text-sm">Payment Method</label>
                        <select name="payment_method" required class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-orange-200">
                            <option value="Cash">Cash</option>
                            <option value="G-cash">G-cash</option>
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
                    <button type="submit" class="px-4 py-2 rounded bg-gradient-to-r from-green-500 to-green-600 text-white shadow">Save Sale</button>
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
</script>
