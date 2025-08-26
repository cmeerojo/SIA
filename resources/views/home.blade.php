<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 px-6 py-4 glass-card">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="text-white font-bold text-xl">RoCorps</div>
            <div class="space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-blue-200 transition">Dashboard</a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('users.index') }}" class="text-white hover:text-blue-200 transition">Users</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-blue-200 transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        Sign Up
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg min-h-screen flex items-center justify-center">
        <div class="text-center text-white px-6">
            <h1 class="text-5xl md:text-7xl font-bold mb-6">
                Ro<span class="text-yellow-300">Corps</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto">
                LPG Online Outlet And Delivery System
            </p>

            <div class="space-x-4 mb-12">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-white text-purple-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition inline-block">
                        Dashboard
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('users.index') }}" class="bg-yellow-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-yellow-400 transition inline-block">
                            Manage Users
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="bg-white text-purple-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition inline-block">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-yellow-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-yellow-400 transition inline-block">
                        Create Account
                    </a>
                @endauth
            </div>
    </section>
</body>
</html>