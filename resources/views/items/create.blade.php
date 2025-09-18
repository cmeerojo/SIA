<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Item
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-8 bg-white p-6 rounded shadow">
        <form action="{{ route('items.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Brand</label>
                <input type="text" name="brand" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Size</label>
                <select name="size" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select size</option>
                    <option value="S">Small (S)</option>
                    <option value="M">Medium (M)</option>
                    <option value="L">Large (L)</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Amount</label>
                <input type="number" name="amount" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Item</button>
                <a href="{{ route('items.index') }}" class="ml-2 text-gray-600">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>