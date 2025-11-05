<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl">Edit Tank</h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('tanks.update', $tank) }}" method="POST" class="bg-white border rounded p-4">
            @csrf
            @method('PATCH')
            <div class="mb-3">
                <label class="block text-sm font-medium">Serial Code</label>
                <input type="text" name="serial_code" value="{{ $tank->serial_code }}" class="w-full mt-1 border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Brand</label>
                <input type="text" name="brand" value="{{ $tank->brand }}" class="w-full mt-1 border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Valve Type</label>
                <select name="valve_type" class="w-full mt-1 border rounded px-3 py-2">
                    <option value="">Select valve type</option>
                    <option value="POL" {{ $tank->valve_type === 'POL' ? 'selected' : '' }}>POL</option>
                    <option value="A/S" {{ $tank->valve_type === 'A/S' ? 'selected' : '' }}>A/S</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="w-full mt-1 border rounded px-3 py-2">
                    <option value="filled" {{ $tank->status === 'filled' ? 'selected' : '' }}>Filled</option>
                    <option value="empty" {{ $tank->status === 'empty' ? 'selected' : '' }}>Empty</option>
                    <option value="with_customer" {{ $tank->status === 'with_customer' ? 'selected' : '' }}>With customer</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
