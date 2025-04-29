<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard Admin - Institut Prima Bangsa</title>
    
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
                        <a href="#" class="sidebar-link active flex items-center text-white px-4 py-3 rounded-lg">
                            <i class="fas fa-tachometer-alt w-6"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center text-slate-400 px-4 py-3 rounded-lg hover:text-white">
                            <i class="fas fa-users w-6"></i>
                            <span>Alumni</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center text-slate-400 px-4 py-3 rounded-lg hover:text-white">
                            <i class="fas fa-briefcase w-6"></i>
                            <span>Pekerjaan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center text-slate-400 px-4 py-3 rounded-lg hover:text-white">
                            <i class="fas fa-graduation-cap w-6"></i>
                            <span>Pendidikan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center text-slate-400 px-4 py-3 rounded-lg hover:text-white">
                            <i class="fas fa-building w-6"></i>
                            <span>Program Studi</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center text-slate-400 px-4 py-3 rounded-lg hover:text-white">
                            <i class="fas fa-user-shield w-6"></i>
                            <span>Pengguna</span>
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
                        <a href="#" class="sidebar-link active flex items-center text-white px-4 py-3 rounded-lg">
                            <i class="fas fa-tachometer-alt w-6"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center text-slate-400 px-4 py-3 rounded-lg hover:text-white">
                            <i class="fas fa-users w-6"></i>
                            <span>Alumni</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center text-slate-400 px-4 py-3 rounded-lg hover:text-white">
                            <i class="fas fa-briefcase w-6"></i>
                            <span>Pekerjaan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center text-slate-400 px-4 py-3 rounded-lg hover:text-white">
                            <i class="fas fa-graduation-cap w-6"></i>
                            <span>Pendidikan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center text-slate-400 px-4 py-3 rounded-lg hover:text-white">
                            <i class="fas fa-building w-6"></i>
                            <span>Program Studi</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center text-slate-400 px-4 py-3 rounded-lg hover:text-white">
                            <i class="fas fa-user-shield w-6"></i>
                            <span>Pengguna</span>
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
                    <h1 class="text-xl font-bold text-white">Dashboard</h1>
                    
                    <div class="flex items-center">
                        <!-- Notifications -->
                        <button class="relative p-2 text-slate-300 hover:text-white mr-4">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">3</span>
                        </button>
                        
                        <!-- Profile -->
                        <div class="relative">
                            <button id="profileDropdown" class="flex items-center text-slate-300 hover:text-white focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white mr-2">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down ml-2"></i>
                            </button>
                            
                            <div id="profileMenu" class="absolute right-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-lg shadow-lg hidden">
                                <ul>
                                    <li><a href="#" class="block px-4 py-2 text-slate-300 hover:bg-slate-700 hover:text-white rounded-t-lg">Profil</a></li>
                                    <li><a href="#" class="block px-4 py-2 text-slate-300 hover:bg-slate-700 hover:text-white">Pengaturan</a></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-red-400 hover:bg-slate-700 hover:text-red-300 rounded-b-lg">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Alumni Card -->
                    <div class="card rounded-xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-white">Total Alumni</h3>
                            <div class="w-12 h-12 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-white mb-1">1,245</p>
                        <p class="text-slate-400 text-sm">+12 alumni baru bulan ini</p>
                    </div>
                    
                    <!-- Bekerja Card -->
                    <div class="card rounded-xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-white">Status Bekerja</h3>
                            <div class="w-12 h-12 rounded-lg bg-green-500/20 text-green-400 flex items-center justify-center">
                                <i class="fas fa-briefcase text-xl"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-white mb-1">78%</p>
                        <p class="text-slate-400 text-sm">+3% dibanding tahun lalu</p>
                    </div>
                    
                    <!-- Studi Lanjut Card -->
                    <div class="card rounded-xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-white">Studi Lanjut</h3>
                            <div class="w-12 h-12 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-xl"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-white mb-1">16%</p>
                        <p class="text-slate-400 text-sm">+2% dibanding tahun lalu</p>
                    </div>
                    
                    <!-- Mencari Kerja Card -->
                    <div class="card rounded-xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-white">Mencari Kerja</h3>
                            <div class="w-12 h-12 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center">
                                <i class="fas fa-search text-xl"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-white mb-1">6%</p>
                        <p class="text-slate-400 text-sm">-5% dibanding tahun lalu</p>
                    </div>
                </div>
                
                <!-- Alumni Terbaru -->
                <div class="card rounded-xl p-6 shadow-lg mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-white">Alumni Terbaru</h3>
                        <a href="#" class="text-primary-400 hover:text-primary-300 text-sm">Lihat Semua</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-left pb-4 text-slate-400 font-medium">Nama</th>
                                    <th class="text-left pb-4 text-slate-400 font-medium">NIM</th>
                                    <th class="text-left pb-4 text-slate-400 font-medium">Tahun Lulus</th>
                                    <th class="text-left pb-4 text-slate-400 font-medium">Status</th>
                                    <th class="text-right pb-4 text-slate-400 font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-t border-slate-800">
                                    <td class="py-4 text-white">Dewi Budianti</td>
                                    <td class="py-4 text-slate-300">2019010001</td>
                                    <td class="py-4 text-slate-300">2023</td>
                                    <td class="py-4">
                                        <span class="bg-green-500/20 text-green-400 px-2 py-1 rounded-full text-xs">Bekerja</span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <a href="#" class="text-primary-400 hover:text-primary-300 px-2"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="text-amber-400 hover:text-amber-300 px-2"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                <tr class="border-t border-slate-800">
                                    <td class="py-4 text-white">Reza Setiawan</td>
                                    <td class="py-4 text-slate-300">2019010012</td>
                                    <td class="py-4 text-slate-300">2023</td>
                                    <td class="py-4">
                                        <span class="bg-green-500/20 text-green-400 px-2 py-1 rounded-full text-xs">Bekerja</span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <a href="#" class="text-primary-400 hover:text-primary-300 px-2"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="text-amber-400 hover:text-amber-300 px-2"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                <tr class="border-t border-slate-800">
                                    <td class="py-4 text-white">Nadia Azizah</td>
                                    <td class="py-4 text-slate-300">2019010023</td>
                                    <td class="py-4 text-slate-300">2023</td>
                                    <td class="py-4">
                                        <span class="bg-purple-500/20 text-purple-400 px-2 py-1 rounded-full text-xs">Studi Lanjut</span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <a href="#" class="text-primary-400 hover:text-primary-300 px-2"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="text-amber-400 hover:text-amber-300 px-2"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                <tr class="border-t border-slate-800">
                                    <td class="py-4 text-white">Farhan Hidayat</td>
                                    <td class="py-4 text-slate-300">2019010045</td>
                                    <td class="py-4 text-slate-300">2023</td>
                                    <td class="py-4">
                                        <span class="bg-amber-500/20 text-amber-400 px-2 py-1 rounded-full text-xs">Mencari Kerja</span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <a href="#" class="text-primary-400 hover:text-primary-300 px-2"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="text-amber-400 hover:text-amber-300 px-2"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                <tr class="border-t border-slate-800">
                                    <td class="py-4 text-white">Anisa Permata</td>
                                    <td class="py-4 text-slate-300">2019010076</td>
                                    <td class="py-4 text-slate-300">2023</td>
                                    <td class="py-4">
                                        <span class="bg-green-500/20 text-green-400 px-2 py-1 rounded-full text-xs">Bekerja</span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <a href="#" class="text-primary-400 hover:text-primary-300 px-2"><i class="fas fa-eye"></i></a>
                                        <a href="#" class="text-amber-400 hover:text-amber-300 px-2"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="card rounded-xl p-6 shadow-lg">
                        <h3 class="text-xl font-semibold text-white mb-6">Aksi Cepat</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <a href="#" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
                                <div class="w-12 h-12 rounded-full bg-primary-600/20 text-primary-400 flex items-center justify-center mb-3">
                                    <i class="fas fa-user-plus text-xl"></i>
                                </div>
                                <span class="text-white text-sm font-medium">Tambah Alumni</span>
                            </a>
                            
                            <a href="#" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
                                <div class="w-12 h-12 rounded-full bg-green-600/20 text-green-400 flex items-center justify-center mb-3">
                                    <i class="fas fa-file-import text-xl"></i>
                                </div>
                                <span class="text-white text-sm font-medium">Import Data</span>
                            </a>
                            
                            <a href="#" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
                                <div class="w-12 h-12 rounded-full bg-amber-600/20 text-amber-400 flex items-center justify-center mb-3">
                                    <i class="fas fa-chart-bar text-xl"></i>
                                </div>
                                <span class="text-white text-sm font-medium">Lihat Laporan</span>
                            </a>
                            
                            <a href="#" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
                                <div class="w-12 h-12 rounded-full bg-purple-600/20 text-purple-400 flex items-center justify-center mb-3">
                                    <i class="fas fa-envelope text-xl"></i>
                                </div>
                                <span class="text-white text-sm font-medium">Kirim Survey</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Distribusi Pekerjaan -->
                    <div class="card rounded-xl p-6 shadow-lg">
                        <h3 class="text-xl font-semibold text-white mb-6">Distribusi Bidang Pekerjaan</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-blue-500 mr-3"></div>
                                <div class="flex-1">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-slate-300">Teknologi Informasi</span>
                                        <span class="text-slate-300">42%</span>
                                    </div>
                                    <div class="w-full bg-slate-700 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: 42%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-purple-500 mr-3"></div>
                                <div class="flex-1">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-slate-300">Pendidikan</span>
                                        <span class="text-slate-300">23%</span>
                                    </div>
                                    <div class="w-full bg-slate-700 rounded-full h-2">
                                        <div class="bg-purple-500 h-2 rounded-full" style="width: 23%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-green-500 mr-3"></div>
                                <div class="flex-1">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-slate-300">Finansial & Perbankan</span>
                                        <span class="text-slate-300">15%</span>
                                    </div>
                                    <div class="w-full bg-slate-700 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: 15%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-amber-500 mr-3"></div>
                                <div class="flex-1">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-slate-300">E-Commerce</span>
                                        <span class="text-slate-300">12%</span>
                                    </div>
                                    <div class="w-full bg-slate-700 rounded-full h-2">
                                        <div class="bg-amber-500 h-2 rounded-full" style="width: 12%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-red-500 mr-3"></div>
                                <div class="flex-1">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-slate-300">Lainnya</span>
                                        <span class="text-slate-300">8%</span>
                                    </div>
                                    <div class="w-full bg-slate-700 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full" style="width: 8%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="bg-slate-800 p-6 mt-6">
                <div class="text-center text-slate-500 text-sm">
                    &copy; {{ date('Y') }} Institut Prima Bangsa - Sistem Pelacakan Alumni PTIK
                </div>
            </footer>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile dropdown toggle
            const profileDropdown = document.getElementById('profileDropdown');
            const profileMenu = document.getElementById('profileMenu');
            
            if (profileDropdown && profileMenu) {
                profileDropdown.addEventListener('click', function() {
                    profileMenu.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!profileDropdown.contains(event.target) && !profileMenu.contains(event.target)) {
                        profileMenu.classList.add('hidden');
                    }
                });
            }
            
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
</body>
</html>