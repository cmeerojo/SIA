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
                        <a href="{{ route('drivers.create') }}" class="bg-blue-600 text-white px-3 py-1 rounded">Add Driver</a>
                    </div>

                    <div class="bg-white rounded">
                        <div class="p-4">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="text-left text-sm text-gray-600">
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($drivers as $d)
                                        <tr class="border-t">
                                            <td class="py-2">{{ $d->name ?? ($d->first_name.' '.$d->last_name) }}</td>
                                            <td class="py-2">{{ $d->contact_number ?? $d->contact_info }}</td>
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
                                        <tr><td colspan="3" class="py-4 text-gray-500">No drivers</td></tr>
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
