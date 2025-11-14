<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">Tank Deliveries</h2>
            <span class="text-gray-500 text-base font-normal">Record and review tank dispatches to customers.</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 shadow-lg sm:rounded-2xl overflow-hidden">
                <div class="p-8 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 text-green-600">{{ session('success') }}</div>
                    @endif

                    <div class="bg-white rounded mb-6">
                        <div class="p-4 border-b">
                            <h3 class="font-semibold">Record Delivery</h3>
                        </div>
                            <div class="p-4">
                            <form action="{{ route('tank.deliveries.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                @csrf
                                <div>
                                    <label class="text-sm">Sale</label>
                                    <select name="sale_id" class="w-full mt-1 border rounded px-3 py-2" required>
                                        @foreach($sales as $s)
                                            <option value="{{ $s->id }}">#{{ $s->id }} — {{ $s->customer?->full_name ?? 'N/A' }} ({{ $s->tanks->count() }} tank{{ $s->tanks->count() === 1 ? '' : 's' }}) — {{ \App\Providers\AppServiceProvider::formatPrettyDate($s->created_at) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm">Driver (optional)</label>
                                    <select name="driver_id" class="w-full mt-1 border rounded px-3 py-2">
                                        <option value="">--</option>
                                        @foreach($drivers as $d)
                                            <option value="{{ $d->id }}">{{ $d->full_name }}{{ $d->license ? ' — ' . $d->license : '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm">Date Delivered (optional)</label>
                                    <input type="datetime-local" name="date_delivered" class="w-full mt-1 border rounded px-3 py-2" />
                                </div>
                                <div class="flex items-end">
                                    <button class="bg-green-600 text-white px-3 py-2 rounded">Record</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white rounded">
                        <div class="p-4 border-b flex items-center justify-between">
                            <h3 class="font-semibold">Recent Tank Deliveries</h3>
                            <div class="relative">
                                <input id="tank-delivery-search" type="text" placeholder="Search date, tanks, customer, driver" class="w-80 border rounded-lg px-3 py-2 pr-9 text-sm focus:ring-2 focus:ring-orange-200" />
                                <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/></svg>
                            </div>
                        </div>
                        <div class="p-4 divide-y" id="tank-delivery-list">
                            @foreach($deliveries as $d)
                                @php
                                    $dDate = $d->date_delivered ? \App\Providers\AppServiceProvider::formatPrettyDate($d->date_delivered) : (\App\Providers\AppServiceProvider::formatPrettyDate($d->created_at ?? null));
                                    $dTanks = $d->sale && $d->sale->tanks->isNotEmpty() ? $d->sale->tanks->pluck('serial_code')->join(', ') : ($d->tank?->serial_code ?? '');
                                    $dCustomer = $d->customer?->full_name ?? '';
                                    $dDriver = $d->driver?->full_name ?? '';
                                @endphp
                                <div class="py-3 flex justify-between items-start" data-date="{{ e($dDate) }}" data-tanks="{{ e($dTanks) }}" data-customer="{{ e($dCustomer) }}" data-driver="{{ e($dDriver) }}">
                                    <div>
                                        <div class="text-sm font-medium">{{ $dDate }} —
                                            @if($d->sale && $d->sale->tanks->isNotEmpty())
                                                Tanks: {{ $dTanks }}
                                            @else
                                                Tank: {{ $d->tank?->serial_code ?? 'N/A' }}
                                            @endif
                                            → Customer: {{ $dCustomer ?: 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">Driver: {{ $dDriver ?: '—' }}{{ $d->driver?->license ? ' — ' . $d->driver->license : '' }}</div>
                                    </div>
                                    <a href="{{ route('tank.deliveries.map', ['tank_delivery' => $d->getRouteKey()]) }}" class="bg-purple-600 hover:bg-purple-700 text-white text-sm px-3 py-1 rounded whitespace-nowrap ml-3">
                                        View Map
                                    </a>
                                </div>
                            @endforeach
                            @if($deliveries->isEmpty())
                                <div class="py-4 text-gray-500">No deliveries recorded</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    (function(){
        const input = document.getElementById('tank-delivery-search');
        const list = document.getElementById('tank-delivery-list');
        if (!input || !list) return;
        const rows = () => list.querySelectorAll('[data-date]');
        const norm = s => (s||'').toString().toLowerCase();
        function apply(){
            const q = norm(input.value);
            rows().forEach(r => {
                const hay = [r.dataset.date, r.dataset.tanks, r.dataset.customer, r.dataset.driver]
                    .map(norm)
                    .join(' ');
                r.style.display = q === '' || hay.includes(q) ? '' : 'none';
            });
        }
        input.addEventListener('input', apply);
    })();
</script>
