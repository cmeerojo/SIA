<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl">Add Tank</h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('tanks.store') }}" method="POST" class="bg-white border rounded p-4">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium">Serial Code</label>
                <input type="text" name="serial_code" class="w-full mt-1 border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Brand</label>
                <input type="text" name="brand" class="w-full mt-1 border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Valve Type</label>
                <select name="valve_type" class="w-full mt-1 border rounded px-3 py-2">
                    <option value="">Select valve type</option>
                    <option value="POL">POL</option>
                    <option value="A/S">A/S</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="w-full mt-1 border rounded px-3 py-2">
                    <option value="filled">Filled</option>
                    <option value="empty">Empty</option>
                    <option value="with_customer">With customer</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Create</button>
            </div>
        </form>
    </div>
</x-app-layout>
