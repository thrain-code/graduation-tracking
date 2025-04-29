@extends('layouts.main')

@section('title', 'Login - Portal Alumni PTIK')

@section('styles')
<style>
    .login-gradient {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }
    
    .login-card {
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(90deg, #0ea5e9, #2563eb);
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        background: linear-gradient(90deg, #0284c7, #1e40af);
        transform: translateY(-2px);
    }
    
    .input-field {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s;
    }
    
    .input-field:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(14, 165, 233, 0.5);
        box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.25);
    }
    
    .particle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
</style>
@endsection

@section('content')
<div class="login-gradient min-h-screen flex flex-col items-center justify-center p-4 relative overflow-hidden">
    <!-- Animated Background -->
    <div id="particles-container" class="absolute inset-0 z-0"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900/30 via-blue-900/20 to-slate-900/30 z-0"></div>
    
    <!-- Login Card -->
    <div class="w-full max-w-md z-10" data-aos="fade-up">
        <!-- Logo and Brand -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center mb-4">
                <i class="fas fa-graduation-cap text-4xl text-primary-500 mr-3"></i>
                <span class="text-white font-bold text-2xl">PTIK Alumni</span>
            </a>
            <h2 class="text-2xl font-bold text-white">Selamat Datang Kembali</h2>
            <p class="text-slate-300 mt-2">Masuk ke akun alumni Anda</p>
        </div>
        
        <!-- Login Form -->
        <div class="login-card rounded-2xl p-8 shadow-2xl">
            @if(session('error'))
                <div class="bg-red-500/20 text-red-400 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif
            
            @if(session('success'))
                <div class="bg-green-500/20 text-green-400 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="login-type" class="block text-sm font-medium text-slate-300 mb-1">Login Sebagai</label>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <label class="flex items-center justify-center bg-slate-800/50 hover:bg-slate-800 border border-slate-700 rounded-md px-3 py-2 cursor-pointer transition">
                            <input type="radio" name="login_type" value="alumni" class="sr-only" checked>
                            <div class="flex items-center">
                                <i class="fas fa-user-graduate text-primary-400 mr-2"></i>
                                <span class="text-white text-sm">Alumni</span>
                            </div>
                        </label>
                        <label class="flex items-center justify-center bg-slate-800/50 hover:bg-slate-800 border border-slate-700 rounded-md px-3 py-2 cursor-pointer transition">
                            <input type="radio" name="login_type" value="admin" class="sr-only">
                            <div class="flex items-center">
                                <i class="fas fa-user-shield text-primary-400 mr-2"></i>
                                <span class="text-white text-sm">Admin</span>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div>
                    <label for="username" class="block text-sm font-medium text-slate-300 mb-1">Username / Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-slate-500"></i>
                        </div>
                        <input id="username" name="username" type="text" autocomplete="username" required class="input-field w-full pl-10 py-3 rounded-lg focus:outline-none text-white" placeholder="Masukkan username atau email">
                    </div>
                    @error('username')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-500"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="input-field w-full pl-10 py-3 rounded-lg focus:outline-none text-white" placeholder="Masukkan password">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-eye text-slate-500"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-slate-600 rounded bg-slate-800">
                        <label for="remember_me" class="ml-2 block text-sm text-slate-300">Ingat Saya</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-sm text-primary-400 hover:text-primary-300">Lupa Password?</a>
                </div>
                
                <div>
                    <button type="submit" class="btn-primary w-full py-3 px-4 rounded-lg text-white font-medium shadow-lg focus:outline-none">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-slate-400">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-primary-400 hover:text-primary-300 font-medium">Daftar sekarang</a>
                </p>
            </div>
        </div>
        
        <!-- Footer Links -->
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-sm text-slate-400 hover:text-white mx-2">
                <i class="fas fa-home mr-1"></i> Beranda
            </a>
            <span class="text-slate-600">|</span>
            <a href="{{ route('alumni.tracking') }}" class="text-sm text-slate-400 hover:text-white mx-2">
                <i class="fas fa-chart-line mr-1"></i> Data Alumni
            </a>
            <span class="text-slate-600">|</span>
            <a href="{{ route('help') }}" class="text-sm text-slate-400 hover:text-white mx-2">
                <i class="fas fa-question-circle mr-1"></i> Bantuan
            </a>
        </div>
        
        <!-- Copyright -->
        <div class="mt-4 text-center">
            <p class="text-xs text-slate-500">
                &copy; {{ date('Y') }} PTIK Institut Prima Bangsa. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle eye icon
                const icon = togglePassword.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }
        
        // Highlight selected login type
        const loginTypeLabels = document.querySelectorAll('input[name="login_type"]');
        if (loginTypeLabels.length > 0) {
            loginTypeLabels.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove active class from all labels
                    document.querySelectorAll('input[name="login_type"]').forEach(r => {
                        r.closest('label').classList.remove('bg-primary-600', 'border-primary-500');
                        r.closest('label').classList.add('bg-slate-800/50', 'border-slate-700');
                    });
                    
                    // Add active class to selected label
                    if (this.checked) {
                        this.closest('label').classList.remove('bg-slate-800/50', 'border-slate-700');
                        this.closest('label').classList.add('bg-primary-600', 'border-primary-500');
                    }
                });
            });
            
            // Set initial active state
            const checkedRadio = document.querySelector('input[name="login_type"]:checked');
            if (checkedRadio) {
                checkedRadio.closest('label').classList.remove('bg-slate-800/50', 'border-slate-700');
                checkedRadio.closest('label').classList.add('bg-primary-600', 'border-primary-500');
            }
        }
        
        // Create animated particles background
        createParticles();
    });
    
    // Create animated particles
    function createParticles() {
        const container = document.getElementById('particles-container');
        if (!container) return;
        
        const particleCount = 30;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            // Random size
            const size = Math.random() * 5 + 1;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            // Random position
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`;
            
            // Random opacity
            particle.style.opacity = Math.random() * 0.5 + 0.1;
            
            // Random animation
            const duration = Math.random() * 15 + 5;
            particle.style.animation = `float ${duration}s infinite ease-in-out`;
            particle.style.animationDelay = `${Math.random() * 5}s`;
            
            container.appendChild(particle);
        }
    }
</script>
@endsection