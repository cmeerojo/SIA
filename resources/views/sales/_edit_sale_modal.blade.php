<x-modal name="edit-sale-modal" :show="false" focusable>
    <form method="POST" action="" id="edit-sale-form" class="p-6">
        @csrf
        @method('PATCH')
        
        <h2 class="text-lg font-medium text-gray-900 mb-4">
            Edit Sale
        </h2>

        <div class="space-y-4">
            <!-- Customer -->
            <div>
                <x-input-label for="edit-customer" value="Customer" />
                <select id="edit-customer" name="customer_id" class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm">
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('customer_id')" />
            </div>

            <!-- Quantity -->
            <div>
                <x-input-label for="edit-quantity" value="Quantity" />
                <x-text-input id="edit-quantity" name="quantity" type="number" min="1" value="1" class="mt-1 block w-full" />
                <p class="text-xs text-gray-500 mt-1">Number of tanks to sell</p>
                <x-input-error class="mt-2" :messages="$errors->get('quantity')" />
            </div>

            <!-- Tank Selection -->
            <div>
                <x-input-label value="Select Tanks (Serial Numbers)" />
                <div id="edit-tank-selection-container" class="mt-1 border border-gray-300 rounded-md p-3 max-h-48 overflow-y-auto">
                    <p class="text-xs text-gray-500 mb-2">Select tanks by checking the boxes below. Quantity must match number of selected tanks.</p>
                    <div class="space-y-2" id="edit-tank-checkboxes">
                        @foreach($tanks as $tank)
                            <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="checkbox" name="tank_ids[]" value="{{ $tank->id }}" class="edit-tank-checkbox border-gray-300 rounded text-orange-600 focus:ring-orange-500" />
                                <span class="text-sm">
                                    {{ $tank->serial_code }} - {{ $tank->brand ?? 'N/A' }} ({{ $tank->size ?? 'N/A' }}) - {{ ucfirst($tank->status) }}
                                    @if($tank->status !== 'filled')
                                        <span class="text-xs text-orange-600">(Already in sale)</span>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @if($tanks->isEmpty())
                        <p class="text-sm text-gray-500">No available tanks (status: filled)</p>
                    @endif
                </div>
                <p class="text-xs text-red-500 mt-1" id="edit-tank-selection-error" style="display: none;">Quantity must match the number of selected tanks.</p>
                <x-input-error class="mt-2" :messages="$errors->get('tank_ids')" />
            </div>

            <!-- Price -->
            <div>
                <x-input-label for="edit-price" value="Price" />
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">₱</span>
                    </div>
                    <x-text-input id="edit-price" name="price" type="number" step="0.01" class="pl-7 block w-full" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('price')" />
            </div>

            <!-- Payment Method -->
            <div>
                <x-input-label for="edit-payment" value="Payment Method" />
                <select id="edit-payment" name="payment_method" class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm">
                    <option value="cash">Cash</option>
                    <option value="gcash">GCash</option>
                    <option value="credit_card">Credit Card</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('payment_method')" />
            </div>

            <!-- Status -->
            <div>
                <x-input-label for="edit-status" value="Status" />
                <select id="edit-status" name="status" class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm">
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('status')" />
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')" class="me-3">
                Cancel
            </x-secondary-button>
            <x-primary-button>
                Save Changes
            </x-primary-button>
        </div>
    </form>
</x-modal>