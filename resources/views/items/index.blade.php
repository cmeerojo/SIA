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
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v4a1 1 0 001 1h3m10-5h3a1 1 0 011 1v4a1 1 0 01-1 1h-3m-10 0v6a1 1 0 001 1h8a1 1 0 001-1v-6m-10 0h10"></path></svg>
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
                        <a href="{{ route('items.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded shadow transition">Add Item</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 bg-white rounded-xl shadow">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Brand</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Size</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->brand }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->size }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->amount }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                                            <a href="{{ route('items.edit', $item) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-1 px-3 rounded shadow">Edit</a>
                                            <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded shadow">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
