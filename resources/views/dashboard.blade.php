<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>
            <div class="text-sm text-gray-600 flex items-center space-x-2">
                <span class="animate-pulse text-green-500">●</span>
                <span>System Online</span>
            </div>
        </div>
    </x-slot>

    <style>
        .lpg-gradient {
            background: linear-gradient(135deg, #f97316, #ea580c, #dc2626);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(249, 115, 22, 0.1);
        }
        
        .admin-card {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(249, 115, 22, 0.1));
            border: 2px solid rgba(239, 68, 68, 0.2);
        }
        
        .user-card {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(16, 185, 129, 0.1));
            border: 2px solid rgba(34, 197, 94, 0.2);
        }
        
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }
        
        .btn-lpg {
            background: linear-gradient(135deg, #ea580c, #dc2626);
            transition: all 0.3s ease;
        }
        
        .btn-lpg:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(234, 88, 12, 0.4);
        }
        
        .btn-admin {
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            transition: all 0.3s ease;
        }
        
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }
        
        .btn-user {
            background: linear-gradient(135deg, #10b981, #059669);
            transition: all 0.3s ease;
        }
        
        .btn-user:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }
        
        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .welcome-text {
            background: linear-gradient(135deg, #ea580c, #dc2626);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>

    <div class="py-8 bg-gradient-to-br from-orange-50 to-red-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Section -->
            <div class="glass-card overflow-hidden sm:rounded-2xl">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-4">
                            <div class="floating-icon text-4xl">🔥</div>
                            <div>
                                <h3 class="text-2xl font-bold welcome-text">
                                    Welcome to RoCorps LPG System
                                </h3>
                                <p class="text-gray-600 mt-1">{{ __("You're logged in and ready to manage your LPG services!") }}</p>
                            </div>
                        </div>
                        <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500">
                            <span>{{ now()->format('M d, Y') }}</span>
                            <span>•</span>
                            <span>{{ now()->format('h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="stat-card glass-card rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Users</p>
                            <p class="text-2xl font-bold text-gray-900">456</p>
                        </div>
                        <div class="text-3xl text-blue-500">👥</div>
                    </div>
                    <div class="mt-2">
                        <span class="text-green-500 text-sm font-medium">+5%</span>
                        <span class="text-gray-500 text-sm"> this week</span>
                    </div>
                </div>

                <div class="stat-card glass-card rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">LPG Delivered</p>
                            <p class="text-2xl font-bold text-gray-900">89</p>
                        </div>
                        <div class="text-3xl text-red-500">🚚</div>
                    </div>
                    <div class="mt-2">
                        <span class="text-green-500 text-sm font-medium">+8%</span>
                        <span class="text-gray-500 text-sm"> today</span>
                    </div>
                </div>

                <div class="stat-card glass-card rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">System Status</p>
                            <p class="text-2xl font-bold text-green-600">Online</p>
                        </div>
                        <div class="text-3xl text-green-500">✅</div>
                    </div>
                    <div class="mt-2">
                        <span class="text-green-500 text-sm font-medium">100%</span>
                        <span class="text-gray-500 text-sm"> uptime</span>
                    </div>
                </div>
            </div>

            <!-- Role-based Panels -->
            @if(Auth::user()->isAdmin())
                <div class="glass-card admin-card overflow-hidden sm:rounded-2xl">
                    <div class="p-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="text-3xl">👑</div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Administrator Panel</h3>
                                <p class="text-sm text-gray-600">You have full system privileges and can manage all aspects of the platform.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- User Management Card -->
                            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="text-2xl text-blue-500">👥</div>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">Management</span>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">User Management</h4>
                                <p class="text-sm text-gray-600 mb-4">Add, edit, and manage user accounts and permissions.</p>
                                <a href="{{ route('users.index') }}" class="btn-admin text-white font-medium py-3 px-6 rounded-lg inline-flex items-center space-x-2 hover:scale-105 transform transition-all">
                                    <span>Manage Users</span>
                                </a>
                            </div>

                            <!-- Profile Management Card -->
                            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="text-2xl text-gray-500">⚙️</div>
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-1 rounded-full">Settings</span>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">My Profile</h4>
                                <p class="text-sm text-gray-600 mb-4">Update your personal information and account settings.</p>
                                <a href="{{ route('profile.edit') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-medium py-3 px-6 rounded-lg inline-flex items-center space-x-2 hover:scale-105 transform transition-all">
                                    <span>Edit Profile</span>
                                </a>
                            </div>

                            <!-- System Analytics Card -->
                            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="text-2xl text-purple-500">📊</div>
                                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-1 rounded-full">Analytics</span>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">System Reports</h4>
                                <p class="text-sm text-gray-600 mb-4">View detailed analytics and system performance metrics.</p>
                                <button class="bg-purple-500 hover:bg-purple-700 text-white font-medium py-3 px-6 rounded-lg inline-flex items-center space-x-2 hover:scale-105 transform transition-all">
                                    <span>Reports</span>
                                </button>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-orange-50 rounded-lg border border-orange-200">
                            <div class="flex items-center space-x-2">
                                <div class="text-orange-500">⚠️</div>
                                <p class="text-sm text-orange-800">
                                    <strong>Admin Notice:</strong> You have administrator privileges and can access all system features. Use these permissions responsibly.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="glass-card user-card overflow-hidden sm:rounded-2xl">
                    <div class="p-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="text-3xl">👤</div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">User Panel</h3>
                                <p class="text-sm text-gray-600">Manage your account and access your LPG services.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Profile Management Card -->
                            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="text-2xl text-green-500">⚙️</div>
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">Profile</span>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Edit Profile</h4>
                                <p class="text-sm text-gray-600 mb-4">Update your personal information, contact details, and preferences.</p>
                                <a href="{{ route('profile.edit') }}" class="btn-user text-white font-medium py-3 px-6 rounded-lg inline-flex items-center space-x-2 hover:scale-105 transform transition-all">
                                    <span>Edit My Profile</span>
                                </a>
                            </div>

                            <!-- Order History Card -->
                            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="text-2xl text-blue-500">📋</div>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">Orders</span>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Order History</h4>
                                <p class="text-sm text-gray-600 mb-4">View your past LPG orders and delivery history.</p>
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg inline-flex items-center space-x-2 hover:scale-105 transform transition-all">
                                    <span>View Orders</span>
                                </button>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-green-50 rounded-lg border border-green-200">
                            <div class="flex items-center space-x-2">
                                <div class="text-green-500">✅</div>
                                <p class="text-sm text-green-800">
                                    <strong>Welcome!</strong> You can manage your profile and track your LPG orders through this dashboard.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions Removed -->

        </div>
    </div>
</x-app-layout>