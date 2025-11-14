<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">Deliveries</h2>
            <span class="text-gray-500 text-base font-normal">Manage drivers and schedule deliveries.</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 shadow-lg sm:rounded-2xl overflow-hidden">
                <div class="p-8 text-gray-900">
                    @if(session('success'))
                        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700 shadow-sm">{{ session('success') }}</div>
                    @endif

                    <div class="mb-6 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v4a1 1 0 001 1h3m10-5h3a1 1 0 011 1v4a1 1 0 01-1 1h-3m-10 0v6a1 1 0 001 1h8a1 1 0 001-1v-6m-10 0h10"></path>
                            </svg>
                            <span class="text-lg font-semibold text-gray-700">Delivery Dashboard</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="document.getElementById('add-driver-modal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">Add Driver</button>
                            <button onclick="document.getElementById('create-delivery-modal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">Create Delivery</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white border rounded p-4">
                            <h3 class="font-bold mb-3">Drivers</h3>
                            <div class="divide-y">
                                @foreach($drivers as $d)
                                    <div class="py-2 flex justify-between items-center">
                                        <div>
                                            <div class="font-medium">{{ $d->first_name }} {{ $d->last_name }}</div>
                                            <div class="text-sm text-gray-600">{{ $d->contact_info }} • {{ $d->license }}</div>
                                        </div>
                                    </div>
                                @endforeach
                                @if($drivers->isEmpty())
                                    <div class="py-4 text-gray-500">No drivers yet</div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-white border rounded p-4">
                            <h3 class="font-bold mb-3">Scheduled Deliveries</h3>
                            <div class="divide-y">
                                @foreach($deliveries as $del)
                                    <div class="py-2 flex items-start justify-between gap-4">
                                        <div>
                                            <div class="font-medium">{{ $del->customer->name }} — {{ $del->item->brand }} ({{ $del->item->size }})</div>
                                            <div class="text-sm text-gray-600">Dropoff: {{ $del->dropoff_location }} • Driver: {{ optional($del->driver)->first_name ?? 'TBD' }} {{ optional($del->driver)->last_name ?? '' }}</div>
                                            <div class="text-sm text-gray-500">Quantity: {{ $del->quantity }} • Scheduled: @prettyDate($del->created_at, true)</div>
                                        </div>
                                        <div class="flex flex-col items-end gap-2">
                                            <div class="flex gap-2">
                                                <a href="{{ route('deliveries.map', $del) }}" class="bg-purple-600 hover:bg-purple-700 text-white text-sm px-3 py-1 rounded">
                                                    View Map
                                                </a>
                                                <form action="{{ route('deliveries.status.update', $del) }}" method="POST" class="inline-flex items-center gap-3">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="border rounded px-2 py-1 text-sm pr-8 truncate">
                                                        <option value="pending" {{ $del->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="ongoing" {{ $del->status === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                        <option value="completed" {{ $del->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                        <option value="cancelled" {{ $del->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                    </select>
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded">Update</button>
                                                </form>
                                            </div>
                                            @php
                                                $statusClasses = [
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'ongoing' => 'bg-emerald-50 text-emerald-700',
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                ];
                                                $badgeClass = $statusClasses[$del->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <div class="text-xs text-gray-400">Status:
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                                    {{ ucfirst($del->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if($deliveries->isEmpty())
                                    <div class="py-4 text-gray-500">No deliveries scheduled</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Add Driver Modal -->
<div id="add-driver-modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-lg w-full border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold">Add Driver</h3>
            <button onclick="document.getElementById('add-driver-modal').classList.add('hidden')" class="text-2xl text-gray-400">&times;</button>
        </div>
        <form action="{{ route('deliveries.drivers.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium">First Name</label>
                    <input type="text" name="first_name" class="w-full mt-1 border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="text-sm font-medium">Last Name</label>
                    <input type="text" name="last_name" class="w-full mt-1 border rounded px-3 py-2" required>
                </div>
            </div>
            <div class="mt-3">
                <label class="text-sm font-medium">Contact Info</label>
                <input type="text" name="contact_info" class="w-full mt-1 border rounded px-3 py-2">
            </div>
            <div class="mt-3">
                <label class="text-sm font-medium">License</label>
                <input type="text" name="license" class="w-full mt-1 border rounded px-3 py-2">
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('add-driver-modal').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">Add Driver</button>
            </div>
        </form>
    </div>
</div>

<!-- Create Delivery Modal -->
<div id="create-delivery-modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 overflow-auto">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-2xl w-full border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold">Create Delivery</h3>
            <button onclick="document.getElementById('create-delivery-modal').classList.add('hidden')" class="text-2xl text-gray-400">&times;</button>
        </div>
        <form action="{{ route('deliveries.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium">Customer</label>
                    <select name="customer_id" id="delivery_customer" class="w-full mt-1 border rounded px-3 py-2" required>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" data-dropoff="{{ e($c->dropoff_location) }}">{{ $c->name }} — {{ $c->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Dropoff Location</label>
                    <input type="text" name="dropoff_location" id="delivery_dropoff" class="w-full mt-1 border rounded px-3 py-2" placeholder="Leave blank to use customer's default">
                </div>
                <div>
                    <label class="text-sm font-medium">Driver</label>
                    <select name="driver_id" class="w-full mt-1 border rounded px-3 py-2" required>
                        @foreach($drivers as $d)
                            <option value="{{ $d->id }}">{{ $d->full_name }}{{ $d->license ? ' — ' . $d->license : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Product</label>
                    <select name="item_id" class="w-full mt-1 border rounded px-3 py-2" required>
                        @foreach($items as $it)
                            <option value="{{ $it->id }}">{{ $it->brand }} ({{ $it->size }}) — available: {{ $it->amount }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Quantity</label>
                    <input type="number" name="quantity" min="1" class="w-full mt-1 border rounded px-3 py-2" required>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('create-delivery-modal').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">Schedule Delivery</button>
            </div>
        </form>
    </div>
</div>

<script>
    // auto-fill dropoff location from selected customer
    document.getElementById('delivery_customer')?.addEventListener('change', function(e) {
        const opt = e.target.selectedOptions[0];
        const dropoff = opt?.dataset?.dropoff || '';
        document.getElementById('delivery_dropoff').value = dropoff;
    });
</script>
