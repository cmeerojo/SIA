<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">Tank {{ $tank->serial_code }} — History</h2>
            <span class="text-gray-500 text-base font-normal">Movement history for this tank — status changes, deliveries and updates.</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 shadow-lg sm:rounded-2xl overflow-hidden">
                <div class="p-8 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <a href="{{ route('items.index') }}" class="text-gray-600">← Back to Items</a>
                    </div>

                    <div class="bg-white rounded">
                        <div class="p-4 border-b">
                            <h3 class="font-semibold">Movements</h3>
                        </div>
                        <div class="p-4 divide-y">
                            @foreach($movements as $m)
                                <div class="py-3">
                                    <div class="text-sm text-gray-700">{{ \App\Providers\AppServiceProvider::formatPrettyDate($m->created_at, true) }} — {{ $m->previous_status ?? 'N/A' }} → {{ $m->new_status }}</div>
                                    <div class="text-xs text-gray-500">Customer: {{ optional($m->customer)->name ?? '—' }} • Driver: {{ optional($m->driver)->name ?? (optional($m->driver)->first_name ?? '—') }}</div>
                                </div>
                            @endforeach
                            @if($movements->isEmpty())
                                <div class="py-4 text-gray-500">No movements recorded</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
