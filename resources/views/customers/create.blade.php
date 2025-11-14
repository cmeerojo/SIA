<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">Add New Customer</h2>
            <span class="text-gray-500 text-base font-normal">Create a comprehensive customer profile with detailed information and notes.</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 shadow-lg sm:rounded-2xl overflow-hidden">
                <div class="p-8 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700 shadow-sm">
                            <div class="font-semibold mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Please fix the following errors:
                            </div>
                            <ul class="list-disc list-inside space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('customers.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Type Toggle -->
                        <div class="flex items-center gap-6">
                            <input type="hidden" name="type" id="customer_type" value="{{ old('type', 'customer') }}">
                            <div class="flex items-center gap-2">
                                <input id="type_customer" type="radio" name="type_choice" value="customer" class="text-blue-600" {{ old('type','customer') === 'customer' ? 'checked' : '' }}>
                                <label for="type_customer" class="text-sm font-medium text-gray-700">Customer</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input id="type_business" type="radio" name="type_choice" value="business" class="text-blue-600" {{ old('type') === 'business' ? 'checked' : '' }}>
                                <label for="type_business" class="text-sm font-medium text-gray-700">Business</label>
                            </div>
                        </div>

                        <!-- Customer (Individual) Fields -->
                        <div id="individual_fields" class="grid grid-cols-1 gap-6 sm:grid-cols-3 mt-4">
                            <div>
                                <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition"
                                       placeholder="First name">
                            </div>
                            <div>
                                <label for="middle_name" class="block text-sm font-semibold text-gray-700 mb-2">Middle Name</label>
                                <input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition"
                                       placeholder="Middle name (optional)">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition"
                                       placeholder="Last name">
                            </div>
                        </div>

                        <!-- Business Fields -->
                        <div id="business_fields" class="grid grid-cols-1 gap-6 sm:grid-cols-3 mt-4" style="display:none;">
                            <div class="sm:col-span-3">
                                <label for="business_name" class="block text-sm font-semibold text-gray-700 mb-2">Business Name *</label>
                                <input id="business_name" type="text" name="business_name" value="{{ old('business_name') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition"
                                       placeholder="Acme Corp">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Business Email *</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition"
                                       placeholder="business@example.com">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition"
                                       placeholder="+1 (555) 123-4567">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-4">
                            <div>
                                <label for="dropoff_location" class="block text-sm font-semibold text-gray-700 mb-2">Dropoff Location</label>
                                <input id="dropoff_location" type="text" name="dropoff_location" value="{{ old('dropoff_location') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition"
                                       placeholder="Address, landmark, or instructions">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition"
                                       placeholder="+1 (555) 123-4567">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description & Notes</label>
                                <textarea id="description" name="description" rows="4" 
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition resize-none"
                                          placeholder="Enter description, preferences, or any relevant notes...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('customers.index') }}"
                               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow transition">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    (function(){
        const typeHidden = document.getElementById('customer_type');
        const rCustomer = document.getElementById('type_customer');
        const rBusiness = document.getElementById('type_business');
        const individual = document.getElementById('individual_fields');
        const business = document.getElementById('business_fields');

        function apply() {
            const t = rBusiness.checked ? 'business' : 'customer';
            typeHidden.value = t;
            if (t === 'business') {
                business.style.display = '';
                individual.style.display = 'none';
            } else {
                business.style.display = 'none';
                individual.style.display = '';
            }
        }

        rCustomer?.addEventListener('change', apply);
        rBusiness?.addEventListener('change', apply);
        // initial state
        apply();
    })();
</script>