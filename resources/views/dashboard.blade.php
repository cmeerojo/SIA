<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                    
                    @if(Auth::user()->isAdmin())
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Panel</h3>
                            <div class="space-x-4">
                                <a href="{{ route('users.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                                    👥 Manage Users
                                </a>
                                <a href="{{ route('profile.edit') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-block">
                                    ⚙️ My Profile
                                </a>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">
                                You have administrator privileges. You can manage all user accounts.
                            </p>
                        </div>
                    @else
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">User Panel</h3>
                            <a href="{{ route('profile.edit') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-block">
                                ⚙️ Edit My Profile
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>