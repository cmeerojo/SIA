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
                        <a href="{{ route('customers.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded shadow transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Customer
                        </a>
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
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $customer->full_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $customer->email }}</td>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $customer->dropoff_location ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                            <a href="{{ route('customers.edit', $customer) }}"
                                               class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-1 px-3 rounded shadow transition">
                                                Edit
                                            </a>
                                            @if(auth()->user() && auth()->user()->role === 'manager')
                                                <button onclick="openDropoffModal({{ $customer->id }}, '{{ addslashes($customer->dropoff_location ?? '') }}')" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded shadow transition">Set Dropoff</button>
                                            @endif
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

<!-- Dropoff Modal -->
<div id="dropoff-modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-lg w-full border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold">Set Dropoff Location</h3>
            <button onclick="document.getElementById('dropoff-modal').classList.add('hidden')" class="text-2xl text-gray-400">&times;</button>
        </div>
        <form id="dropoff-form" method="POST" action="">
            @csrf
            @method('PATCH')
            <div class="mb-3">
                <label class="text-sm font-medium">Dropoff Location</label>
                <input type="text" name="dropoff_location" id="dropoff_location_input" class="w-full mt-1 border rounded px-3 py-2" placeholder="Enter address, landmark, or instructions">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('dropoff-modal').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDropoffModal(customerId, existing) {
        const modal = document.getElementById('dropoff-modal');
        const input = document.getElementById('dropoff_location_input');
        const form = document.getElementById('dropoff-form');
        input.value = existing || '';
        form.action = `/customers/${customerId}/dropoff`;
        modal.classList.remove('hidden');
    }
</script>