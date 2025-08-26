<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                            
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-3">
                        @can('update', $user)
                            <a href="{{ route('users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit Profile
                            </a>
                        @endcan

                        @can('viewAny', App\Models\User::class)
                            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to Users
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>