<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RoCorps - LPG Online Outlet & Delivery</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'slide-up': 'slideUp 0.8s ease-out forwards',
                        'fade-in': 'fadeIn 1s ease-out forwards',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(50px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        glow: {
                            '0%': { boxShadow: '0 0 20px rgba(255, 255, 255, 0.3)' },
                            '100%': { boxShadow: '0 0 40px rgba(255, 255, 255, 0.6)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: 
                linear-gradient(135deg, rgba(220, 38, 127, 0.9) 0%, rgba(239, 68, 68, 0.8) 30%, rgba(249, 115, 22, 0.9) 100%),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3Ccircle cx='10' cy='10' r='2'/%3E%3Ccircle cx='50' cy='50' r='3'/%3E%3Ccircle cx='10' cy='50' r='2'/%3E%3Ccircle cx='50' cy='10' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            background-size: 200% 200%, 60px 60px;
            animation: gradientShift 8s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%, 0 0; }
            50% { background-position: 100% 50%, 30px 30px; }
            100% { background-position: 0% 50%, 0 0; }
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .glass-button {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .glass-button:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .hero-text {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .floating-elements::before,
        .floating-elements::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(249, 115, 22, 0.1);
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-elements::before {
            width: 120px;
            height: 120px;
            top: 15%;
            left: 8%;
            animation-delay: -2s;
            background: radial-gradient(circle, rgba(239, 68, 68, 0.2), rgba(249, 115, 22, 0.1));
        }
        
        .floating-elements::after {
            width: 180px;
            height: 180px;
            top: 65%;
            right: 8%;
            animation-delay: -4s;
            background: radial-gradient(circle, rgba(220, 38, 127, 0.15), rgba(239, 68, 68, 0.1));
        }
        
        .nav-link {
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #fb923c, #ea580c);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ea580c, #dc2626);
            box-shadow: 0 4px 15px rgba(234, 88, 12, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(234, 88, 12, 0.6);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            color: #6366f1;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
        }
        
        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.5);
        }
        
        .logo-glow {
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3));
        }
    </style>
</head>
<body class="antialiased overflow-x-hidden">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 px-6 py-4 glass-card animate-fade-in">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="text-white font-bold text-2xl logo-glow animate-pulse-slow">
                Ro<span class="text-orange-300">Corps</span>
            </div>
            <div class="hidden md:flex space-x-6">
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-link text-white hover:text-yellow-300 transition-colors duration-300 py-2">Dashboard</a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('users.index') }}" class="nav-link text-white hover:text-yellow-300 transition-colors duration-300 py-2">Users</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="glass-button text-white px-4 py-2 rounded-lg font-medium">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link text-white hover:text-yellow-300 transition-colors duration-300 py-2">Login</a>
                    <a href="{{ route('register') }}" class="glass-button text-white px-4 py-2 rounded-lg font-medium hover:text-white">
                        Sign Up
                    </a>
                @endauth
            </div>
            
            <!-- Mobile menu button -->
            <button class="md:hidden glass-button text-white p-2 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg min-h-screen flex items-center justify-center relative floating-elements overflow-hidden">
        <!-- LPG Tank Icons and Fire Elements -->
        <div class="absolute inset-0">
            <!-- Floating LPG Tank Icons -->
            <div class="absolute top-1/4 left-1/6 opacity-20 animate-float text-6xl" style="animation-delay: -1s;">🔥</div>
            <div class="absolute top-2/3 left-1/4 opacity-15 animate-float text-4xl" style="animation-delay: -3s;">⛽</div>
            <div class="absolute top-1/3 right-1/6 opacity-20 animate-float text-5xl" style="animation-delay: -2s;">🔥</div>
            <div class="absolute top-3/4 right-1/3 opacity-15 animate-float text-3xl" style="animation-delay: -4s;">⚡</div>
            
            <!-- LPG Tank Silhouettes -->
            <div class="absolute top-1/5 left-1/12 opacity-10 animate-pulse-slow">
                <svg width="40" height="60" viewBox="0 0 40 60" fill="currentColor" class="text-white">
                    <rect x="10" y="10" width="20" height="40" rx="10" fill="currentColor"/>
                    <circle cx="20" cy="8" r="3" fill="currentColor"/>
                    <rect x="18" y="5" width="4" height="6" fill="currentColor"/>
                </svg>
            </div>
            <div class="absolute top-2/3 right-1/12 opacity-10 animate-pulse-slow" style="animation-delay: 2s;">
                <svg width="35" height="50" viewBox="0 0 35 50" fill="currentColor" class="text-orange-200">
                    <rect x="7" y="8" width="21" height="35" rx="10" fill="currentColor"/>
                    <circle cx="17.5" cy="6" r="2.5" fill="currentColor"/>
                    <rect x="16" y="4" width="3" height="4" fill="currentColor"/>
                </svg>
            </div>
            
            <!-- Gas flame particles -->
            <div class="absolute top-1/6 left-2/3 w-2 h-2 bg-orange-400 rounded-full opacity-60 animate-ping"></div>
            <div class="absolute top-5/6 left-1/3 w-1 h-1 bg-red-400 rounded-full opacity-80 animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 right-1/5 w-3 h-3 bg-yellow-400 rounded-full opacity-40 animate-ping" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/8 right-2/3 w-1 h-1 bg-orange-300 rounded-full opacity-70 animate-pulse" style="animation-delay: 3s;"></div>
        </div>
        
        <div class="text-center text-white px-6 max-w-4xl mx-auto relative z-10">
            <!-- Logo/Title -->
            <div class="animate-slide-up mb-8">
                <h1 class="text-6xl md:text-8xl font-black mb-4 hero-text tracking-tight">
                    Ro<span class="text-orange-300 animate-glow">Corps</span>
                </h1>
                <div class="w-24 h-1 bg-gradient-to-r from-orange-400 to-red-600 mx-auto rounded-full animate-pulse-slow"></div>
            </div>

            <!-- Subtitle -->
            <div class="animate-slide-up mb-12" style="animation-delay: 0.2s;">
                <p class="text-2xl md:text-3xl mb-4 font-light hero-text">
                    LPG Online Outlet & Delivery System
                </p>
                <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto leading-relaxed">
                    Experience seamless LPG ordering and delivery with our cutting-edge platform. 
                    Fast, reliable, and convenient service at your fingertips.
                </p>
            </div>

            <!-- Call to Action Buttons -->
            <div class="animate-slide-up space-y-4 md:space-y-0 md:space-x-6 md:flex md:justify-center mb-16" style="animation-delay: 0.4s;">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-secondary inline-block px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105">
                        Go to Dashboard
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('users.index') }}" class="btn-primary inline-block px-8 py-4 rounded-xl font-bold text-lg text-white transition-all duration-300 transform hover:scale-105">
                            Manage Users
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-secondary inline-block px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary inline-block px-8 py-4 rounded-xl font-bold text-lg text-white transition-all duration-300 transform hover:scale-105">
                        Create Account
                    </a>
                @endauth
            </div>

            <!-- Features Preview -->
            <div class="animate-slide-up grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto" style="animation-delay: 0.6s;">
                <div class="glass-card rounded-xl p-6 hover:scale-105 transition-transform duration-300">
                    <div class="text-orange-300 text-4xl mb-3">🚚</div>
                    <h3 class="font-bold text-lg mb-2">Fast LPG Delivery</h3>
                    <p class="opacity-80 text-sm">Quick and safe LPG cylinder delivery to your location</p>
                </div>
                <div class="glass-card rounded-xl p-6 hover:scale-105 transition-transform duration-300">
                    <div class="text-red-300 text-4xl mb-3">🔥</div>
                    <h3 class="font-bold text-lg mb-2">Quality Gas Supply</h3>
                    <p class="opacity-80 text-sm">Premium quality LPG for all your cooking and heating needs</p>
                </div>
                <div class="glass-card rounded-xl p-6 hover:scale-105 transition-transform duration-300">
                    <div class="text-yellow-300 text-4xl mb-3">⛽</div>
                    <h3 class="font-bold text-lg mb-2">Tank Exchange</h3>
                    <p class="opacity-80 text-sm">Easy cylinder exchange and refill services</p>
                </div>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center">
                <div class="w-1 h-2 bg-white rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </section>

    <script>
        // Add smooth scrolling and intersection observer animations
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            // Navbar background on scroll
            window.addEventListener('scroll', function() {
                const nav = document.querySelector('nav');
                if (window.scrollY > 50) {
                    nav.style.background = 'rgba(255, 255, 255, 0.15)';
                } else {
                    nav.style.background = 'rgba(255, 255, 255, 0.1)';
                }
            });

            // Add parallax effect to floating elements
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const parallax = document.querySelector('.floating-elements');
                const speed = scrolled * 0.5;
                parallax.style.transform = `translateY(${speed}px)`;
            });
        });
    </script>
</body>
</html>