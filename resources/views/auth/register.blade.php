<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - RoCorps LPG</title>
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
            min-height: 100vh;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%, 0 0; }
            50% { background-position: 100% 50%, 30px 30px; }
            100% { background-position: 0% 50%, 0 0; }
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .glass-input {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .glass-input:focus {
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(249, 115, 22, 0.5);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ea580c, #dc2626);
            box-shadow: 0 4px 15px rgba(234, 88, 12, 0.4);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(234, 88, 12, 0.6);
        }
        
        .floating-elements::before,
        .floating-elements::after {
            content: '';
            position: absolute;
            border-radius: 50%;
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
        
        .logo-glow {
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3));
        }
        
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .input-group {
            position: relative;
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            transform: translateY(-2px);
        }
        
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="antialiased">
    <div class="gradient-bg flex items-center justify-center px-6 py-12 floating-elements">
        <!-- LPG themed background elements -->
        <div class="absolute inset-0">
            <!-- Floating LPG Tank Icons -->
            <div class="absolute top-1/4 left-1/6 opacity-15 animate-float text-5xl" style="animation-delay: -1s;">🔥</div>
            <div class="absolute top-2/3 left-1/4 opacity-10 animate-float text-3xl" style="animation-delay: -3s;">⛽</div>
            <div class="absolute top-1/3 right-1/6 opacity-15 animate-float text-4xl" style="animation-delay: -2s;">🔥</div>
            <div class="absolute top-3/4 right-1/3 opacity-10 animate-float text-2xl" style="animation-delay: -4s;">⚡</div>
            
            <!-- LPG Tank Silhouettes -->
            <div class="absolute top-1/5 left-1/12 opacity-8 animate-pulse-slow">
                <svg width="30" height="45" viewBox="0 0 30 45" fill="currentColor" class="text-white">
                    <rect x="7" y="7" width="16" height="30" rx="8" fill="currentColor"/>
                    <circle cx="15" cy="6" r="2.5" fill="currentColor"/>
                    <rect x="13.5" y="4" width="3" height="4" fill="currentColor"/>
                </svg>
            </div>
            <div class="absolute top-2/3 right-1/12 opacity-8 animate-pulse-slow" style="animation-delay: 2s;">
                <svg width="25" height="40" viewBox="0 0 25 40" fill="currentColor" class="text-orange-200">
                    <rect x="5" y="6" width="15" height="28" rx="7" fill="currentColor"/>
                    <circle cx="12.5" cy="5" r="2" fill="currentColor"/>
                    <rect x="11.5" y="3" width="2" height="4" fill="currentColor"/>
                </svg>
            </div>
            
            <!-- Gas flame particles -->
            <div class="absolute top-1/6 left-2/3 w-1 h-1 bg-orange-400 rounded-full opacity-60 animate-ping"></div>
            <div class="absolute top-5/6 left-1/3 w-2 h-2 bg-red-400 rounded-full opacity-50 animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 right-1/5 w-1 h-1 bg-yellow-400 rounded-full opacity-40 animate-ping" style="animation-delay: 2s;"></div>
        </div>

        <div class="w-full max-w-md relative z-10">
            <!-- Logo/Header -->
            <div class="text-center mb-8 animate-fade-in">
                <h1 class="text-4xl md:text-5xl font-black text-white mb-2 logo-glow text-shadow">
                    Ro<span class="text-orange-300">Corps</span>
                </h1>
                <p class="text-white opacity-90 text-lg">LPG Online Outlet</p>
                <div class="w-16 h-1 bg-gradient-to-r from-orange-400 to-red-600 mx-auto mt-3 rounded-full"></div>
            </div>

            <!-- Registration Form -->
            <div class="glass-card rounded-2xl p-8 animate-slide-up">
                <h2 class="text-2xl font-bold text-white mb-2 text-center">Create Account</h2>
                <p class="text-white opacity-75 text-center mb-6 text-sm">Join RoCorps for seamless LPG delivery</p>
                
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div class="input-group">
                        <label for="name" class="block text-sm font-medium text-white mb-2">
                            {{ __('Full Name') }}
                        </label>
                        <input 
                            id="name" 
                            class="glass-input block w-full px-4 py-3 rounded-lg border-0 text-gray-900 placeholder-gray-500 focus:ring-0 focus:outline-none transition-all duration-300" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            autocomplete="name"
                            placeholder="Enter your full name"
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-300 text-sm" />
                    </div>

                    <!-- Email Address -->
                    <div class="input-group">
                        <label for="email" class="block text-sm font-medium text-white mb-2">
                            {{ __('Email Address') }}
                        </label>
                        <input 
                            id="email" 
                            class="glass-input block w-full px-4 py-3 rounded-lg border-0 text-gray-900 placeholder-gray-500 focus:ring-0 focus:outline-none transition-all duration-300" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="username"
                            placeholder="Enter your email address"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300 text-sm" />
                    </div>

                    <!-- Password -->
                    <div class="input-group">
                        <label for="password" class="block text-sm font-medium text-white mb-2">
                            {{ __('Password') }}
                        </label>
                        <input 
                            id="password" 
                            class="glass-input block w-full px-4 py-3 rounded-lg border-0 text-gray-900 placeholder-gray-500 focus:ring-0 focus:outline-none transition-all duration-300"
                            type="password"
                            name="password"
                            required 
                            autocomplete="new-password"
                            placeholder="Create a strong password"
                        />
                        <!-- Password Strength Indicator -->
                        <div class="password-strength mt-2 bg-gray-300" id="password-strength">
                            <div class="h-full bg-gradient-to-r from-red-500 to-green-500 rounded transition-all duration-300" style="width: 0%;"></div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300 text-sm" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="input-group">
                        <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">
                            {{ __('Confirm Password') }}
                        </label>
                        <input 
                            id="password_confirmation" 
                            class="glass-input block w-full px-4 py-3 rounded-lg border-0 text-gray-900 placeholder-gray-500 focus:ring-0 focus:outline-none transition-all duration-300"
                            type="password"
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="Confirm your password"
                        />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-300 text-sm" />
                    </div>

                    <!-- Terms Agreement -->
                    <div class="flex items-start space-x-3 bg-white bg-opacity-10 rounded-lg p-3">
                        <input 
                            id="terms" 
                            type="checkbox" 
                            class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded mt-0.5" 
                            required
                        >
                        <label for="terms" class="text-sm text-white leading-relaxed">
                            I agree to the <a href="#" class="text-orange-300 hover:text-orange-200 underline">Terms of Service</a> 
                            and <a href="#" class="text-orange-300 hover:text-orange-200 underline">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="space-y-4 pt-2">
                        <button 
                            type="submit" 
                            class="btn-primary w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:scale-[1.02] transform transition-all duration-300"
                        >
                            {{ __('Create Account') }}
                        </button>

                        <!-- Login Link -->
                        <div class="text-center pt-4 border-t border-white border-opacity-20">
                            <p class="text-white text-sm">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="text-orange-300 hover:text-orange-200 font-medium transition-colors duration-300">
                                    Sign in here
                                </a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Additional Info -->
            <div class="text-center mt-6 animate-fade-in" style="animation-delay: 0.3s;">
                <p class="text-white text-sm opacity-75">
                    Join thousands of satisfied customers using RoCorps LPG delivery
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password strength indicator
            const passwordInput = document.getElementById('password');
            const strengthIndicator = document.querySelector('#password-strength div');
            
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                // Check password criteria
                if (password.length >= 8) strength += 25;
                if (password.match(/[a-z]+/)) strength += 25;
                if (password.match(/[A-Z]+/)) strength += 25;
                if (password.match(/[0-9]+/) || password.match(/[!@#$%^&*(),.?":{}|<>]/)) strength += 25;
                
                strengthIndicator.style.width = strength + '%';
                
                // Update color based on strength
                if (strength < 50) {
                    strengthIndicator.className = 'h-full bg-red-500 rounded transition-all duration-300';
                } else if (strength < 75) {
                    strengthIndicator.className = 'h-full bg-yellow-500 rounded transition-all duration-300';
                } else {
                    strengthIndicator.className = 'h-full bg-green-500 rounded transition-all duration-300';
                }
            });

            // Add focus effects to inputs
            const inputs = document.querySelectorAll('.glass-input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('scale-105');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('scale-105');
                });
            });

            // Password confirmation validation
            const passwordConfirm = document.getElementById('password_confirmation');
            passwordConfirm.addEventListener('input', function() {
                if (this.value && this.value !== passwordInput.value) {
                    this.style.borderColor = 'rgba(239, 68, 68, 0.5)';
                } else {
                    this.style.borderColor = 'rgba(249, 115, 22, 0.5)';
                }
            });

            // Form submission loading state
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', function() {
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Creating Account...
                `;
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html>