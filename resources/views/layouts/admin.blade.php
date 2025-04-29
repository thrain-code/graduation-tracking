<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Sistem Pelacakan Alumni PTIK')</title>
    
    <!-- Font utama -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                    },
                },
            },
        }
    </script>
    
    <!-- ChartJS (untuk dashboard) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.05);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(15, 23, 42, 0.2);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(15, 23, 42, 0.3);
        }
        
        /* Sidebar active state */
        .sidebar-active {
            background-color: #0ea5e9;
            color: white;
        }
        
        .sidebar-active:hover {
            background-color: #0284c7;
        }
        
        /* Fade animations */
        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    
    @yield('styles')
</head>
<body class="antialiased">
    <!-- Mobile Sidebar Toggle -->
    <div class="fixed top-4 left-4 z-50 md:hidden">
        <button id="sidebarToggle" class="bg-white p-2 rounded-md shadow-md text-slate-700 hover:bg-slate-100">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-slate-800 text-white w-64 flex-shrink-0 hidden md:block fixed md:relative z-40 h-full transition-all duration-300 ease-in-out transform md:translate-x-0">
            <div class="flex flex-col h-full">
                <!-- Logo / Header -->
                <div class="px-6 py-4 border-b border-slate-700 flex items-center">
                    <i class="fas fa-graduation-cap text-2xl text-primary-500 mr-3"></i>
                    <h1 class="text-xl font-bold">PTIK Alumni</h1>
                </div>
                
                <!-- Admin Info -->
                <div class="px-6 py-4 border-b border-slate-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-primary-700 flex items-center justify-center text-sm font-bold">
                            {{ substr(auth()->user()->username ?? 'Admin', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium">{{ auth()->user()->username ?? 'Admin' }}</p>
                            <p class="text-xs text-slate-400">Administrator</p>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <nav class="flex-1 px-3 py-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-sm rounded-md font-medium {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : 'text-slate-300 hover:bg-slate-700' }}">
                        <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.alumni') }}" class="flex items-center px-3 py-2 text-sm rounded-md font-medium {{ request()->routeIs('admin.alumni*') ? 'sidebar-active' : 'text-slate-300 hover:bg-slate-700' }}">
                        <i class="fas fa-user-graduate mr-3 w-5 text-center"></i>
                        Data Alumni
                    </a>
                    
                    <a href="{{ route('admin.pekerjaan') }}" class="flex items-center px-3 py-2 text-sm rounded-md font-medium {{ request()->routeIs('admin.pekerjaan*') ? 'sidebar-active' : 'text-slate-300 hover:bg-slate-700' }}">
                        <i class="fas fa-briefcase mr-3 w-5 text-center"></i>
                        Data Pekerjaan
                    </a>
                    
                    <a href="{{ route('admin.pendidikan') }}" class="flex items-center px-3 py-2 text-sm rounded-md font-medium {{ request()->routeIs('admin.pendidikan*') ? 'sidebar-active' : 'text-slate-300 hover:bg-slate-700' }}">
                        <i class="fas fa-graduation-cap mr-3 w-5 text-center"></i>
                        Pendidikan Lanjutan
                    </a>
                    
                    <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-2 text-sm rounded-md font-medium {{ request()->routeIs('admin.users*') ? 'sidebar-active' : 'text-slate-300 hover:bg-slate-700' }}">
                        <i class="fas fa-users mr-3 w-5 text-center"></i>
                        Manajemen User
                    </a>
                    
                    <a href="{{ route('admin.reports') }}" class="flex items-center px-3 py-2 text-sm rounded-md font-medium {{ request()->routeIs('admin.reports*') ? 'sidebar-active' : 'text-slate-300 hover:bg-slate-700' }}">
                        <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
                        Laporan
                    </a>
                    
                    <div class="pt-3 mt-3 border-t border-slate-700">
                        <a href="{{ route('admin.settings') }}" class="flex items-center px-3 py-2 text-sm rounded-md font-medium {{ request()->routeIs('admin.settings*') ? 'sidebar-active' : 'text-slate-300 hover:bg-slate-700' }}">
                            <i class="fas fa-cog mr-3 w-5 text-center"></i>
                            Pengaturan
                        </a>
                        
                        <form action="{{ route('logout') }}" method="POST" class="mt-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-3 py-2 text-sm rounded-md font-medium text-slate-300 hover:bg-slate-700">
                                <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm px-6 py-3 sticky top-0 z-30">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h2>
                    
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button id="notificationButton" class="text-slate-500 hover:text-slate-700">
                                <i class="far fa-bell text-lg"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                        </div>
                        
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center text-sm text-slate-700 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-sm font-bold text-white mr-2">
                                    {{ substr(auth()->user()->username ?? 'A', 0, 1) }}
                                </div>
                                <span class="hidden md:block">{{ auth()->user()->username ?? 'Admin' }}</span>
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            
                            <!-- User Dropdown Menu -->
                            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                    <i class="fas fa-user mr-2"></i> Profil
                                </a>
                                <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                    <i class="fas fa-cog mr-2"></i> Pengaturan
                                </a>
                                <div class="border-t border-gray-200 my-1"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Flash Messages -->
            @if(session('success') || session('error'))
                <div class="px-6 pt-4">
                    @if(session('success'))
                        <div class="flash-message bg-green-500 text-white px-4 py-3 rounded shadow-md transition-opacity duration-500">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="flash-message bg-red-500 text-white px-4 py-3 rounded shadow-md transition-opacity duration-500">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <p>{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            
            <!-- Page Content -->
            <div class="px-6 py-6 fade-in">
                @yield('content')
            </div>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-6 py-4">
                <div class="text-center text-sm text-slate-500">
                    &copy; {{ date('Y') }} PTIK Institut Prima Bangsa. Sistem Pelacakan Alumni.
                </div>
            </footer>
        </main>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('hidden');
                });
            }
            
            // User dropdown toggle
            const userMenuButton = document.getElementById('userMenuButton');
            const userDropdown = document.getElementById('userDropdown');
            
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
            
            // Auto-hide flash messages
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(message => {
                setTimeout(() => {
                    message.classList.add('opacity-0');
                    setTimeout(() => {
                        message.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>