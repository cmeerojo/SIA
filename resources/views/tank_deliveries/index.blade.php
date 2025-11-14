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
                        <div class="p-4 border-b">
                            <h3 class="font-semibold">Recent Tank Deliveries</h3>
                        </div>
                        <div class="p-4 divide-y">
                            @foreach($deliveries as $d)
                                <div class="py-3 flex justify-between items-start">
                                    <div>
                                        <div class="text-sm font-medium">{{ $d->date_delivered ? \App\Providers\AppServiceProvider::formatPrettyDate($d->date_delivered) : (\App\Providers\AppServiceProvider::formatPrettyDate($d->created_at ?? null)) }} —
                                            @if($d->sale && $d->sale->tanks->isNotEmpty())
                                                Tanks: {{ $d->sale->tanks->pluck('serial_code')->join(', ') }}
                                            @else
                                                Tank: {{ $d->tank?->serial_code ?? 'N/A' }}
                                            @endif
                                            → Customer: {{ $d->customer?->full_name ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">Driver: {{ $d->driver?->full_name ?? '—' }}{{ $d->driver?->license ? ' — ' . $d->driver->license : '' }}</div>
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
