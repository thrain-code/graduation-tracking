<!-- layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Admin Dashboard') - Institut Prima Bangsa</title>
    
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
    
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f172a;
            color: #f8fafc;
        }
        
        .sidebar {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-link {
            transition: all 0.3s;
        }
        
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .content-area {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
        }
        
        .card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="sidebar fixed w-64 h-full overflow-y-auto p-4 hidden lg:block">
            <div class="flex items-center mb-8">
                <i class="fas fa-university text-3xl text-primary-500 mr-3"></i>
                <span class="text-white font-bold text-xl">Admin PTIK</span>
            </div>
            
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active text-white' : 'text-slate-400 hover:text-white' }} flex items-center px-4 py-3 rounded-lg">
                            <i class="fas fa-tachometer-alt w-6"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link {{ request()->routeIs('admin.alumni*') ? 'active text-white' : 'text-slate-400 hover:text-white' }} flex items-center px-4 py-3 rounded-lg">
                            <i class="fas fa-users w-6"></i>
                            <span>Alumni</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('prodi.index') }}" class="sidebar-link {{ request()->routeIs('admin.prodi*') ? 'active text-white' : 'text-slate-400 hover:text-white' }} flex items-center px-4 py-3 rounded-lg">
                            <i class="fas fa-building w-6"></i>
                            <span>Program Studi</span>
                        </a>
                    </li>
                    <li class="pt-4 mt-4 border-t border-slate-800">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full sidebar-link flex items-center text-red-400 px-4 py-3 rounded-lg hover:text-red-300">
                                <i class="fas fa-sign-out-alt w-6"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Mobile Sidebar Toggle -->
        <div class="fixed top-0 left-0 p-4 lg:hidden z-20">
            <button id="sidebarToggle" class="text-white bg-primary-600 p-3 rounded-lg">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden lg:hidden"></div>
        
        <!-- Mobile Sidebar -->
        <aside id="mobileSidebar" class="sidebar fixed w-64 h-full overflow-y-auto p-4 z-20 -left-64 lg:hidden transition-all duration-300">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <i class="fas fa-university text-3xl text-primary-500 mr-3"></i>
                    <span class="text-white font-bold text-xl">Admin PTIK</span>
                </div>
                <button id="closeSidebar" class="text-white p-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active text-white' : 'text-slate-400 hover:text-white' }} flex items-center px-4 py-3 rounded-lg">
                            <i class="fas fa-tachometer-alt w-6"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link {{ request()->routeIs('admin.alumni*') ? 'active text-white' : 'text-slate-400 hover:text-white' }} flex items-center px-4 py-3 rounded-lg">
                            <i class="fas fa-users w-6"></i>
                            <span>Alumni</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link {{ request()->routeIs('admin.prodi*') ? 'active text-white' : 'text-slate-400 hover:text-white' }} flex items-center px-4 py-3 rounded-lg">
                            <i class="fas fa-building w-6"></i>
                            <span>Program Studi</span>
                        </a>
                    </li>
                    <li class="pt-4 mt-4 border-t border-slate-800">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full sidebar-link flex items-center text-red-400 px-4 py-3 rounded-lg hover:text-red-300">
                                <i class="fas fa-sign-out-alt w-6"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 ml-0 lg:ml-64 min-h-screen">
            <!-- Header -->
            <header class="bg-slate-800 p-4 shadow-md sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-bold text-white">@yield('page-title', 'Dashboard')</h1>
                    
                    <div class="flex items-center">
                        <!-- Notifications -->
                        <button class="relative p-2 text-slate-300 hover:text-white mr-4">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">3</span>
                        </button>
                    </div>
                </div>
            </header>
            
            <!-- Alert Success -->
            @if(session('success'))
            <div class="bg-green-500/20 text-green-400 px-4 py-3 m-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
            @endif
            
            <!-- Content -->
            <div class="p-4 md:p-6">
                @yield('content')
            </div>
            
            <!-- Footer -->
            <footer class="bg-slate-800 p-6 mt-6">
                <div class="text-center text-slate-500 text-sm">
                    &copy; {{ date('Y') }} Institut Prima Bangsa - Sistem Pelacakan Alumni PTIK
                </div>
            </footer>
        </main>
    </div>
    
    @yield('modals')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileSidebar = document.getElementById('mobileSidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const closeSidebar = document.getElementById('closeSidebar');
            
            if (sidebarToggle && mobileSidebar && sidebarOverlay && closeSidebar) {
                sidebarToggle.addEventListener('click', function() {
                    mobileSidebar.classList.remove('-left-64');
                    mobileSidebar.classList.add('left-0');
                    sidebarOverlay.classList.remove('hidden');
                });
                
                function hideSidebar() {
                    mobileSidebar.classList.remove('left-0');
                    mobileSidebar.classList.add('-left-64');
                    sidebarOverlay.classList.add('hidden');
                }
                
                closeSidebar.addEventListener('click', hideSidebar);
                sidebarOverlay.addEventListener('click', hideSidebar);
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>