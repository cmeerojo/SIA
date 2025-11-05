<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">Edit Driver</h2>
            <span class="text-gray-500 text-base font-normal">Update driver contact or name information.</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 shadow-lg sm:rounded-2xl overflow-hidden">
                <div class="p-8 text-gray-900">
                    <form action="{{ route('drivers.update', $driver) }}" method="POST" class="bg-white border rounded p-4">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="block text-sm font-medium">Name</label>
                            <input type="text" name="name" value="{{ $driver->name ?? ($driver->first_name.' '.$driver->last_name) }}" class="w-full mt-1 border rounded px-3 py-2" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium">Contact Number</label>
                            <input type="text" name="contact_number" value="{{ $driver->contact_number ?? $driver->contact_info }}" class="w-full mt-1 border rounded px-3 py-2">
                        </div>
                        <div class="flex justify-end">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
