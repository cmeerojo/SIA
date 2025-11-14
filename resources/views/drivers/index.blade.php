<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">Drivers</h2>
            <span class="text-gray-500 text-base font-normal">Manage drivers who perform deliveries.</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 shadow-lg sm:rounded-2xl overflow-hidden">
                <div class="p-8 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        @if(session('success'))
                            <div class="text-green-600">{{ session('success') }}</div>
                        @endif
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <input id="driver-search" type="text" placeholder="Search name, contact, license" class="w-72 border rounded-lg px-3 py-2 pr-9 text-sm focus:ring-2 focus:ring-orange-200" />
                                <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/></svg>
                            </div>
                            <a href="{{ route('drivers.create') }}" class="bg-blue-600 text-white px-3 py-1 rounded">Add Driver</a>
                        </div>
                    </div>

                    <div class="bg-white rounded">
                        <div class="p-4">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="text-left text-sm text-gray-600">
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>License</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($drivers as $d)
                                        @php
                                            $dName = $d->full_name;
                                            $dContact = $d->contact_number ?? $d->contact_info ?? '';
                                            $dLicense = $d->license ?? '';
                                        @endphp
                                        <tr class="border-t" data-name="{{ e($dName) }}" data-contact="{{ e($dContact) }}" data-license="{{ e($dLicense) }}">
                                                    <td class="py-2">{{ $dName }}</td>
                                                    <td class="py-2">{{ $dContact }}</td>
                                                    <td class="py-2">{{ $d->license ?? '—' }}</td>
                                                    <td class="py-2 text-right">
                                                <a href="{{ route('drivers.edit', $d) }}" class="text-gray-600">Edit</a>
                                                <form action="{{ route('drivers.destroy', $d) }}" method="POST" class="inline-block ml-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-red-600">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($drivers->isEmpty())
                                        <tr><td colspan="4" class="py-4 text-gray-500">No drivers</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    (function(){
        const input = document.getElementById('driver-search');
        const table = document.querySelector('table');
        if (!input || !table) return;
        const rows = () => table.querySelectorAll('tbody tr');
        const norm = s => (s||'').toString().toLowerCase();
        function apply(){
            const q = norm(input.value);
            rows().forEach(r => {
                const hay = [r.dataset.name, r.dataset.contact, r.dataset.license].map(norm).join(' ');
                r.style.display = q === '' || hay.includes(q) ? '' : 'none';
            });
        }
        input.addEventListener('input', apply);
    })();
</script>
