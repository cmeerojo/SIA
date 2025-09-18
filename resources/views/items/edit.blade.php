<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Item @isset($item->id)#{{ $item->id }}@endisset
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="mb-6 rounded border border-red-200 bg-red-50 p-4 text-red-700">
                            <div class="font-semibold mb-2">Please fix the following:</div>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('items.update', $item) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                                <input
                                    id="brand"
                                    type="text"
                                    name="brand"
                                    value="{{ old('brand', $item->brand) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                            </div>

                            <div>
                                <label for="size" class="block text-sm font-medium text-gray-700">Size</label>
                                <select id="size" name="size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Select size</option>
                                    <option value="S" {{ old('size', $item->size) == 'S' ? 'selected' : '' }}>Small (S)</option>
                                    <option value="M" {{ old('size', $item->size) == 'M' ? 'selected' : '' }}>Medium (M)</option>
                                    <option value="L" {{ old('size', $item->size) == 'L' ? 'selected' : '' }}>Large (L)</option>
                                </select>
                            </div>

                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                <input
                                    id="amount"
                                    type="number"
                                    name="amount"
                                    value="{{ old('amount', $item->amount) }}"
                                    min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('items.index') }}"
                               class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Update Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Optional: Back link --}}
            <div class="mt-4 text-right">
                <a href="{{ route('items.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Back to Items
                </a>
            </div>
        </div>
    </div>
</x-app-layout>