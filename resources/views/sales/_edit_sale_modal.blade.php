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
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('customer_id')" />
            </div>

            <!-- Tank -->
            <div>
                <x-input-label for="edit-tank" value="Tank" />
                <select id="edit-tank" name="tank_id" class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm">
                    <option value="">Select Tank</option>
                    @foreach($tanks as $tank)
                        <option value="{{ $tank->id }}">{{ $tank->serial_code }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('tank_id')" />
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