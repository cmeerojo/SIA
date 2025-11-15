<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">Customer Management</h2>
            <span class="text-gray-500 text-base font-normal">Maintain comprehensive customer records with detailed information and professional organization.</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 shadow-lg sm:rounded-2xl overflow-hidden">
                <div class="p-8 text-gray-900">

                    @if (session('success'))
                        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700 shadow-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <!-- Customer Statistics Node -->
                    <div class="mb-8">
                        <div class="bg-white border border-gray-200 shadow-md rounded-lg p-6 flex flex-col items-center max-w-xs">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                                </svg>
                                <span class="font-semibold text-lg text-gray-700">Total Customers</span>
                            </div>
                            <div class="text-3xl font-bold text-blue-700">{{ $totalCustomers }}</div>
                        </div>
                    </div>

                    <div class="mb-6 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                            </svg>
                            <span class="text-lg font-semibold text-gray-700">Customer Directory</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <input id="customer-search" type="text" placeholder="Search name, email, phone, dropoff, notes" class="w-80 border rounded-lg px-3 py-2 pr-9 text-sm focus:ring-2 focus:ring-orange-200" />
                                <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/></svg>
                            </div>
                            <a href="{{ route('customers.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded shadow transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Customer
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Dropoff</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($customers as $customer)
                                    @php
                                        $searchName = trim(($customer->full_name ?? '') . ' ' . ($customer->name ?? ''));
                                        $searchEmail = $customer->email ?? '';
                                        $searchPhone = $customer->phone ?? '';
                                        $searchDrop = $customer->dropoff_location ?? '';
                                        $searchDesc = $customer->description ?? '';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors" data-name="{{ e($searchName) }}" data-email="{{ e($searchEmail) }}" data-phone="{{ e($searchPhone) }}" data-dropoff="{{ e($searchDrop) }}" data-desc="{{ e($searchDesc) }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $customer->full_name }}</td>
                                        @php $isIndividual = !empty($customer->first_name) || !empty($customer->last_name); @endphp
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $isIndividual ? '—' : ($customer->email ?? '—') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $customer->phone ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                            @if($customer->description)
                                                <div class="truncate" title="{{ $customer->description }}">
                                                    {{ Str::limit($customer->description, 50) }}
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">No description</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $customer->dropoff_location ?? '—' }}
                                            @if($customer->dropoff_landmark)
                                                <span class="block text-xs text-gray-500">Landmark: {{ $customer->dropoff_landmark }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                            <a href="{{ route('customers.edit', $customer) }}"
                                               class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-1 px-3 rounded shadow transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Are you sure you want to delete this customer? This action cannot be undone.');"
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded shadow transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                                </svg>
                                                <div class="text-gray-500 text-lg font-medium">No customers found</div>
                                                <div class="text-gray-400 text-sm">Get started by adding your first customer</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    (function(){
        const input = document.getElementById('customer-search');
        const table = document.querySelector('table');
        if (!input || !table) return;
        const rows = () => table.querySelectorAll('tbody tr');
        const norm = s => (s||'').toString().toLowerCase();
        function apply(){
            const q = norm(input.value);
            rows().forEach(r => {
                const hay = [r.dataset.name, r.dataset.email, r.dataset.phone, r.dataset.dropoff, r.dataset.desc]
                    .map(norm)
                    .join(' ');
                r.style.display = q === '' || hay.includes(q) ? '' : 'none';
            });
        }
        input.addEventListener('input', apply);
    })();
</script>

<!-- Dropoff modal removed; set in create/edit forms now -->