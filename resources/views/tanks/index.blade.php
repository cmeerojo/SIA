<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl">Tanks</h2>
            <a href="{{ route('tanks.create') }}" class="bg-blue-600 text-white px-3 py-1 rounded">Add Tank</a>
        </div>
    </x-slot>

    <div class="p-6">
        @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto bg-white border rounded p-4">
            <table class="min-w-full">
                <thead>
                    <tr class="text-left text-sm text-gray-600">
                        <th>Serial</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tanks as $tank)
                        <tr class="border-t">
                            <td class="py-2">{{ $tank->serial_code }}</td>
                            <td class="py-2">{{ ucfirst($tank->status) }}</td>
                            <td class="py-2">@prettyDate($tank->created_at)</td>
                            <td class="py-2 text-right">
                                <a href="{{ route('tanks.show', $tank) }}" class="text-blue-600">History</a>
                                <a href="{{ route('tanks.edit', $tank) }}" class="ml-2 text-gray-600">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    @if($tanks->isEmpty())
                        <tr><td colspan="4" class="py-4 text-gray-500">No tanks yet</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
