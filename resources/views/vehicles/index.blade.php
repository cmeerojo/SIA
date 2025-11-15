<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800">Vehicles</h2>
            <button onclick="document.getElementById('add-vehicle-modal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Add Vehicle</button>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 shadow-lg sm:rounded-2xl overflow-hidden">
                <div class="p-8 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 text-green-600">{{ session('success') }}</div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Color</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plate Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Added</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($vehicles as $v)
                                    <tr>
                                        <td class="px-6 py-4 text-sm">{{ $v->model }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $v->color ?: '—' }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold">{{ $v->plate_number }}</td>
                                        <td class="px-6 py-4 text-sm">@prettyDate($v->created_at, true)</td>
                                        <td class="px-6 py-4 text-sm">
                                            <form action="{{ route('vehicles.destroy', $v) }}" method="POST" onsubmit="return confirm('Remove vehicle?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($vehicles->isEmpty())
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">No vehicles added yet.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Add Vehicle Modal -->
<div id="add-vehicle-modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-lg w-full border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold">Add Vehicle</h3>
            <button onclick="document.getElementById('add-vehicle-modal').classList.add('hidden')" class="text-2xl text-gray-400">&times;</button>
        </div>
        <form action="{{ route('vehicles.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Model</label>
                    <input type="text" name="model" class="w-full mt-1 border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="text-sm font-medium">Color (optional)</label>
                    <input type="text" name="color" class="w-full mt-1 border rounded px-3 py-2" placeholder="e.g. White">
                </div>
                <div>
                    <label class="text-sm font-medium">Plate Number</label>
                    <input type="text" name="plate_number" class="w-full mt-1 border rounded px-3 py-2" required>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="document.getElementById('add-vehicle-modal').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">Add Vehicle</button>
            </div>
        </form>
    </div>
</div>
