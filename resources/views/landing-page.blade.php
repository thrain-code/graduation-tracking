@extends('layouts.main')

@section('title', 'Portal Alumni PTIK - Institut Prima Bangsa')

@section('styles')
<style>
    .gradient-text {
        background: linear-gradient(90deg, #0ea5e9, #6366f1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .feature-icon {
        background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        transition: all 0.3s;
    }
    
    .card-hover:hover .feature-icon {
        transform: scale(1.05);
    }
    
    .btn-gradient {
        background: linear-gradient(90deg, #0ea5e9, #2563eb);
        transition: all 0.3s;
    }
    
    .btn-gradient:hover {
        background: linear-gradient(90deg, #0284c7, #1e40af);
        transform: translateY(-2px);
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    
    .floating {
        animation: float 8s ease-in-out infinite;
    }
    
    /* Animated particles */
    .particle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
    }
</style>
@endsection

@section('content')
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 backdrop-blur-lg bg-slate-900/80 border-b border-slate-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="#" class="flex items-center">
                        <i class="fas fa-graduation-cap text-2xl text-primary-500 mr-3"></i>
                        <span class="text-white font-bold text-xl">PTIK Alumni</span>
                    </a>
                    <div class="hidden md:ml-8 md:flex md:space-x-6">
                        <a href="#features" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Fitur</a>
                        <a href="#testimonials" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Testimoni</a>
                        <a href="#statistics" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Statistik</a>
                        <a href="#faq" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">FAQ</a>
                        <a href="#" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Data Alumni</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="#" class="hidden md:inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                    <a href="#" class="hidden md:ml-3 md:inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-user-plus mr-2"></i> Register
                    </a>
                    <button type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-slate-800 focus:outline-none" id="mobile-menu-button">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#features" class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Fitur</a>
                <a href="#testimonials" class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Testimoni</a>
                <a href="#statistics" class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Statistik</a>
                <a href="#faq" class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">FAQ</a>
                <a href="#" class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Data Alumni</a>
                <div class="pt-4 pb-3 border-t border-gray-700">
                    <div class="flex items-center px-5">
                        <a href="#" class="w-full block text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </a>
                    </div>
                    <div class="mt-3 px-5">
                        <a href="#" class="w-full block text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-user-plus mr-2"></i> Register
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-16 px-4 sm:px-6 lg:px-8">
        <!-- Background Effects -->
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-900/50 to-slate-900 z-0"></div>
            <div id="particles-container" class="absolute inset-0 z-0"></div>
        </div>
        
        <div class="container mx-auto max-w-7xl z-10 pt-20 pb-24">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <h5 class="text-primary-400 font-semibold tracking-widest mb-2 flex items-center">
                        <span class="bg-primary-400/20 rounded-full w-2 h-2 mr-2"></span>
                        PORTAL ALUMNI PTIK 
                        <span class="bg-primary-400/20 rounded-full w-2 h-2 ml-2"></span>
                    </h5>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight text-white">
                        Terhubung & <span class="gradient-text">Berkembang</span> Bersama
                    </h1>
                    <p class="text-slate-300 text-lg md:text-xl mb-8 leading-relaxed">
                        Platform digital untuk menghubungkan alumni PTIK Institut Prima Bangsa. Jalin networking, akses informasi karir, dan ikuti perkembangan sesama alumni.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#" class="btn-gradient text-white font-semibold py-3 px-8 rounded-xl shadow-lg inline-flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i> Masuk Portal
                        </a>
                        <a href="#" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white font-semibold py-3 px-8 rounded-xl inline-flex items-center justify-center border border-white/10">
                            <i class="fas fa-chart-line mr-2"></i> Lihat Statistik
                        </a>
                    </div>
                    <div class="mt-10 flex items-center">
                        <div class="flex -space-x-2">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" class="w-10 h-10 rounded-full border-2 border-white" alt="User">
                            <img src="https://randomuser.me/api/portraits/men/44.jpg" class="w-10 h-10 rounded-full border-2 border-white" alt="User">
                            <img src="https://randomuser.me/api/portraits/women/55.jpg" class="w-10 h-10 rounded-full border-2 border-white" alt="User">
                            <img src="https://randomuser.me/api/portraits/men/36.jpg" class="w-10 h-10 rounded-full border-2 border-white" alt="User">
                            <div class="w-10 h-10 rounded-full border-2 border-white bg-primary-600 flex items-center justify-center text-xs text-white font-bold">
                                +1K
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-white font-semibold">Bergabunglah dengan 1000+ alumni</p>
                            <p class="text-slate-400 text-sm">yang telah terhubung di platform kami</p>
                        </div>
                    </div>
                </div>
                
                <div class="hidden md:flex justify-center relative" data-aos="fade-left">
                    <div class="relative w-full max-w-md">
                        <div class="absolute -top-8 -left-8 w-24 h-24 bg-blue-500/20 rounded-full filter blur-xl"></div>
                        <div class="absolute -bottom-10 -right-8 w-32 h-32 bg-purple-500/20 rounded-full filter blur-xl"></div>
                        
                        <div class="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl border border-slate-700/50 rounded-2xl overflow-hidden shadow-xl floating">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-6">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold">
                                            DB
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-white font-semibold">Dewi Budianti</h4>
                                            <p class="text-slate-400 text-sm">Software Engineer at Tokopedia</p>
                                        </div>
                                    </div>
                                    <div class="bg-blue-500/20 rounded-full p-2">
                                        <i class="fas fa-check text-blue-400"></i>
                                    </div>
                                </div>
                                
                                <div class="bg-slate-800/50 rounded-xl p-4 mb-4">
                                    <div class="flex justify-between items-center mb-3">
                                        <h5 class="text-white font-medium">Status Karir</h5>
                                        <span class="text-xs bg-green-500/20 text-green-400 py-1 px-2 rounded-full">Bekerja</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-slate-400 text-sm">Perusahaan</span>
                                            <span class="text-white text-sm">Tokopedia</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400 text-sm">Posisi</span>
                                            <span class="text-white text-sm">Software Engineer</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400 text-sm">Tahun Lulus</span>
                                            <span class="text-white text-sm">2019</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2 mb-4">
                                    <div class="bg-slate-800/50 rounded-xl p-3 flex-1 text-center">
                                        <h5 class="text-primary-400 text-2xl font-bold">15</h5>
                                        <p class="text-slate-400 text-xs">Koneksi</p>
                                    </div>
                                    <div class="bg-slate-800/50 rounded-xl p-3 flex-1 text-center">
                                        <h5 class="text-purple-400 text-2xl font-bold">8</h5>
                                        <p class="text-slate-400 text-xs">Acara</p>
                                    </div>
                                    <div class="bg-slate-800/50 rounded-xl p-3 flex-1 text-center">
                                        <h5 class="text-amber-400 text-2xl font-bold">3</h5>
                                        <p class="text-slate-400 text-xs">Mentoring</p>
                                    </div>
                                </div>
                                
                                <button class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-xl transition">
                                    Lihat Profil Lengkap
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Partners Section -->
    <section class="py-12 border-t border-slate-800/50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6">
                <h4 class="text-slate-400 text-sm uppercase tracking-wider">Perusahaan Mitra & Alumni Bekerja di</h4>
            </div>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16">
                <div class="text-slate-400 text-2xl opacity-60 hover:opacity-100 transition">
                    <i class="fab fa-google"></i>
                </div>
                <div class="text-slate-400 text-2xl opacity-60 hover:opacity-100 transition">
                    <i class="fab fa-microsoft"></i>
                </div>
                <div class="text-slate-400 text-2xl opacity-60 hover:opacity-100 transition">
                    <i class="fab fa-amazon"></i>
                </div>
                <div class="text-slate-400 text-2xl opacity-60 hover:opacity-100 transition">
                    <img src="{{ asset('img/tokopedia.svg') }}" alt="Tokopedia" class="h-6 grayscale opacity-80 hover:opacity-100 transition">
                </div>
                <div class="text-slate-400 text-2xl opacity-60 hover:opacity-100 transition">
                    <img src="{{ asset('img/gojek.svg') }}" alt="Gojek" class="h-6 grayscale opacity-80 hover:opacity-100 transition">
                </div>
                <div class="text-slate-400 text-2xl opacity-60 hover:opacity-100 transition">
                    <img src="{{ asset('img/traveloka.svg') }}" alt="Traveloka" class="h-6 grayscale opacity-80 hover:opacity-100 transition">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto max-w-7xl">
            <div class="text-center mb-16" data-aos="fade-up">
                <h5 class="text-primary-500 font-semibold tracking-wider mb-2">FITUR UTAMA</h5>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Manfaat Bergabung dengan Portal Alumni PTIK</h2>
                <p class="text-slate-300 max-w-3xl mx-auto">Akses berbagai fitur eksklusif yang dirancang khusus untuk mendukung pengembangan karier dan jejaring profesional alumni PTIK Institut Prima Bangsa.</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-6 card-hover" data-aos="fade-up">
                    <div class="feature-icon w-14 h-14">
                        <i class="fas fa-network-wired text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Jaringan Alumni</h3>
                    <p class="text-slate-300 mb-4">Terhubung dengan ribuan alumni PTIK dari berbagai angkatan dan berbagai sektor industri di seluruh dunia.</p>
                    <a href="#" class="text-primary-400 hover:text-primary-300 inline-flex items-center text-sm font-medium">
                        Lihat Selengkapnya 
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-6 card-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon w-14 h-14">
                        <i class="fas fa-briefcase text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Info Karir Eksklusif</h3>
                    <p class="text-slate-300 mb-4">Akses lowongan pekerjaan eksklusif dari perusahaan partner dan alumni yang membuka rekrutmen.</p>
                    <a href="#" class="text-primary-400 hover:text-primary-300 inline-flex items-center text-sm font-medium">
                        Lihat Selengkapnya 
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-6 card-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon w-14 h-14">
                        <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Program Mentoring</h3>
                    <p class="text-slate-300 mb-4">Dapatkan bimbingan karier dari alumni senior yang sukses di bidangnya atau jadilah mentor bagi juniormu.</p>
                    <a href="#" class="text-primary-400 hover:text-primary-300 inline-flex items-center text-sm font-medium">
                        Lihat Selengkapnya 
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-6 card-hover" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon w-14 h-14">
                        <i class="fas fa-calendar-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Event & Gathering</h3>
                    <p class="text-slate-300 mb-4">Ikuti berbagai acara reuni, seminar, workshop, dan gathering alumni yang diadakan secara berkala.</p>
                    <a href="#" class="text-primary-400 hover:text-primary-300 inline-flex items-center text-sm font-medium">
                        Lihat Selengkapnya 
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-6 card-hover" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-icon w-14 h-14">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Informasi Studi Lanjut</h3>
                    <p class="text-slate-300 mb-4">Akses informasi tentang beasiswa, program pascasarjana, dan berbagi pengalaman studi lanjut.</p>
                    <a href="#" class="text-primary-400 hover:text-primary-300 inline-flex items-center text-sm font-medium">
                        Lihat Selengkapnya 
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-6 card-hover" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-icon w-14 h-14">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">Statistik & Tracer</h3>
                    <p class="text-slate-300 mb-4">Lihat visualisasi data alumni, distribusi karier, dan pemetaan lokasi alumni secara real-time.</p>
                    <a href="#" class="text-primary-400 hover:text-primary-300 inline-flex items-center text-sm font-medium">
                        Lihat Selengkapnya 
                        <i class="fas fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 px-4 sm:px-6 lg:px-8 bg-slate-900/50">
        <div class="container mx-auto max-w-7xl">
            <div class="text-center mb-16" data-aos="fade-up">
                <h5 class="text-primary-500 font-semibold tracking-wider mb-2">TESTIMONI</h5>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Apa Kata Alumni Kami</h2>
                <p class="text-slate-300 max-w-3xl mx-auto">Pengalaman alumni yang telah menggunakan platform kami dan merasakan manfaat dari jaringan alumni PTIK.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gradient-to-br from-slate-800/70 to-slate-900/70 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-6" data-aos="fade-up">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 rounded-full overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Reza Setiawan" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="text-white font-semibold">Reza Setiawan</h4>
                            <p class="text-primary-400 text-sm">Full-stack Developer di Gojek</p>
                            <p class="text-slate-400 text-xs">Angkatan 2020</p>
                        </div>
                    </div>
                    <p class="text-slate-300 mb-4">"Program magang dan kerjasama industri dari jurusan membantu saya mendapatkan pekerjaan bahkan sebelum lulus. Network alumni juga sangat membantu saya berkembang di industri teknologi."</p>
                    <div class="flex text-amber-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-gradient-to-br from-slate-800/70 to-slate-900/70 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 rounded-full overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Dewi Budianti" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="text-white font-semibold">Dewi Budianti</h4>
                            <p class="text-primary-400 text-sm">Software Engineer di Tokopedia</p>
                            <p class="text-slate-400 text-xs">Angkatan 2019</p>
                        </div>
                    </div>
                    <p class="text-slate-300 mb-4">"Portal alumni PTIK membuka banyak peluang networking yang sangat berharga. Saya bisa terhubung dengan alumni senior yang membantu saya mendapatkan posisi saat ini di Tokopedia."</p>
                    <div class="flex text-amber-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="bg-gradient-to-br from-slate-800/70 to-slate-900/70 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 rounded-full overflow-hidden mr-4">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Nadia Azizah" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="text-white font-semibold">Nadia Azizah</h4>
                            <p class="text-primary-400 text-sm">UI/UX Designer di Traveloka</p>
                            <p class="text-slate-400 text-xs">Angkatan 2021</p>
                        </div>
                    </div>
                    <p class="text-slate-300 mb-4">"Fitur mentoring di platform ini sangat membantu karir saya. Mendapatkan wawasan dan bimbingan langsung dari senior yang sudah berpengalaman sangat berharga untuk perkembangan karir."</p>
                    <div class="flex text-amber-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="" class="inline-flex items-center text-primary-400 hover:text-primary-300 font-medium">
                    Lihat Semua Testimoni
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Statistics Section -->
    <section id="statistics" class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto max-w-7xl">
            <div class="text-center mb-16" data-aos="fade-up">
                <h5 class="text-primary-500 font-semibold tracking-wider mb-2">STATISTIK</h5>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Distribusi Alumni PTIK</h2>
                <p class="text-slate-300 max-w-3xl mx-auto">Melihat sebaran dan pencapaian alumni PTIK Institut Prima Bangsa dari berbagai angkatan.</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stat 1 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-6 text-center" data-aos="fade-up">
                    <div class="w-16 h-16 bg-blue-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-white mb-2">1,250+</h3>
                    <p class="text-slate-400">Total Alumni</p>
                </div>
                
                <!-- Stat 2 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-6 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-green-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-briefcase text-green-400 text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-white mb-2">85%</h3>
                    <p class="text-slate-400">Tingkat Penyerapan Kerja</p>
                </div>
                
                <!-- Stat 3 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-6 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-purple-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-graduation-cap text-purple-400 text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-white mb-2">10%</h3>
                    <p class="text-slate-400">Melanjutkan Studi S2/S3</p>
                </div>
                
                <!-- Stat 4 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl p-6 text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-amber-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-globe text-amber-400 text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-white mb-2">25+</h3>
                    <p class="text-slate-400">Tersebar di Negara</p>
                </div>
            </div>
            
            <div class="mt-12 text-center">
                <a href="#" class="inline-block btn-gradient text-white font-semibold py-3 px-8 rounded-xl shadow-lg">
                    Lihat Statistik Lengkap
                </a>
            </div>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section id="faq" class="py-20 px-4 sm:px-6 lg:px-8 bg-slate-900/50">
        <div class="container mx-auto max-w-4xl">
            <div class="text-center mb-16" data-aos="fade-up">
                <h5 class="text-primary-500 font-semibold tracking-wider mb-2">FAQ</h5>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Pertanyaan Umum</h2>
                <p class="text-slate-300 max-w-2xl mx-auto">Temukan jawaban untuk pertanyaan yang sering diajukan tentang portal alumni PTIK.</p>
            </div>
            
            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl" data-aos="fade-up">
                    <button class="faq-toggle w-full text-left px-6 py-4 focus:outline-none flex items-center justify-between">
                        <h4 class="font-semibold text-white">Bagaimana cara mendaftar ke portal alumni?</h4>
                        <i class="faq-icon fas fa-chevron-down h-5 w-5 text-primary-400 transform transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-slate-300">Alumni dapat mendaftar dengan mengklik tombol "Register" pada halaman utama. Kemudian isi formulir dengan data diri serta bukti kelulusan dari PTIK. Tim admin akan memverifikasi dan mengaktifkan akun dalam 1-2 hari kerja.</p>
                    </div>
                </div>
                
                <!-- FAQ Item 2 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl" data-aos="fade-up" data-aos-delay="50">
                    <button class="faq-toggle w-full text-left px-6 py-4 focus:outline-none flex items-center justify-between">
                        <h4 class="font-semibold text-white">Apa saja fitur utama yang tersedia di portal alumni?</h4>
                        <i class="faq-icon fas fa-chevron-down h-5 w-5 text-primary-400 transform transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-slate-300">Portal alumni menyediakan berbagai fitur seperti direktori alumni, info lowongan kerja, program mentoring, forum diskusi, data statistik, dan informasi mengenai event dan reuni yang akan datang.</p>
                    </div>
                </div>
                
                <!-- FAQ Item 3 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl" data-aos="fade-up" data-aos-delay="100">
                    <button class="faq-toggle w-full text-left px-6 py-4 focus:outline-none flex items-center justify-between">
                        <h4 class="font-semibold text-white">Bagaimana cara mengupdate data saya di portal alumni?</h4>
                        <i class="faq-icon fas fa-chevron-down h-5 w-5 text-primary-400 transform transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-slate-300">Setelah login, kunjungi halaman "Profil Saya" dan klik tombol "Edit Profil". Di sana Anda dapat memperbarui informasi kontak, pengalaman kerja, pendidikan lanjutan, dan informasi lainnya. Jangan lupa menekan tombol "Simpan" setelah selesai.</p>
                    </div>
                </div>
                
                <!-- FAQ Item 4 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl" data-aos="fade-up" data-aos-delay="150">
                    <button class="faq-toggle w-full text-left px-6 py-4 focus:outline-none flex items-center justify-between">
                        <h4 class="font-semibold text-white">Apakah ada biaya untuk menggunakan portal alumni?</h4>
                        <i class="faq-icon fas fa-chevron-down h-5 w-5 text-primary-400 transform transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-slate-300">Tidak, portal alumni PTIK dapat digunakan secara gratis oleh semua alumni. Semua fitur tersedia tanpa biaya tambahan sebagai bentuk layanan dari almamater kepada para alumninya.</p>
                    </div>
                </div>
                
                <!-- FAQ Item 5 -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-sm border border-slate-700/30 rounded-2xl" data-aos="fade-up" data-aos-delay="200">
                    <button class="faq-toggle w-full text-left px-6 py-4 focus:outline-none flex items-center justify-between">
                        <h4 class="font-semibold text-white">Bagaimana cara berpartisipasi dalam program mentoring?</h4>
                        <i class="faq-icon fas fa-chevron-down h-5 w-5 text-primary-400 transform transition-transform"></i>
                    </button>
                    <div class="faq-content hidden px-6 pb-4">
                        <p class="text-slate-300">Untuk berpartisipasi dalam program mentoring, kunjungi menu "Program Mentoring" di dashboard Anda. Di sana Anda dapat mendaftar sebagai mentor atau mentee. Jika mendaftar sebagai mentee, Anda dapat memilih mentor yang sesuai dengan minat dan tujuan karier Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-primary-600/30 to-blue-700/30 z-0"></div>
        <div class="absolute inset-0 opacity-30 z-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z\' fill=\'%23ffffff\' fill-opacity=\'0.1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E')"></div>
        
        <div class="container mx-auto max-w-5xl relative z-10">
            <div class="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-xl rounded-2xl p-8 md:p-12 shadow-2xl border border-slate-700/30" data-aos="fade-up">
                <div class="flex flex-col md:flex-row md:items-center">
                    <div class="flex-1 mb-8 md:mb-0 md:mr-8">
                        <h2 class="text-3xl font-bold text-white mb-4">Bergabung dengan Komunitas Alumni PTIK</h2>
                        <p class="text-slate-300 mb-4">Jangan lewatkan kesempatan untuk terhubung dengan sesama alumni, mengakses peluang karier, dan terus mengembangkan jaringan profesional Anda.</p>
                        <div class="flex flex-wrap gap-4 mt-6">
                            <a href="#" class="btn-gradient text-white font-semibold py-3 px-8 rounded-xl shadow-lg inline-flex items-center justify-center">
                                <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                            </a>
                            <a href="#" class="bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white font-semibold py-3 px-8 rounded-xl inline-flex items-center justify-center border border-white/10">
                                <i class="fas fa-sign-in-alt mr-2"></i> Login
                            </a>
                        </div>
                    </div>
                    <div class="flex-shrink-0 flex flex-col items-center">
                        <div class="w-28 h-28 rounded-full bg-primary-600/20 flex items-center justify-center border border-primary-500/30">
                            <i class="fas fa-graduation-cap text-4xl text-primary-400"></i>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-sm text-primary-400">Bergabung bersama</p>
                            <p class="text-2xl font-bold text-white">1,250+ Alumni</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="bg-slate-900 py-12 px-4 sm:px-6 lg:px-8 border-t border-slate-800">
        <div class="container mx-auto max-w-7xl">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="flex items-center mb-6">
                        <i class="fas fa-graduation-cap text-3xl text-primary-500 mr-3"></i>
                        <h3 class="text-xl font-bold text-white">PTIK Alumni</h3>
                    </div>
                    <p class="text-slate-400 mb-6">Portal resmi alumni Pendidikan Teknik Informatika dan Komputer Institut Prima Bangsa. Terhubung, Berkembang, dan Berbagi bersama.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-slate-400 hover:text-white">
                            <i class="fab fa-facebook-f h-5 w-5"></i>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-white">
                            <i class="fab fa-twitter h-5 w-5"></i>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-white">
                            <i class="fab fa-instagram h-5 w-5"></i>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-white">
                            <i class="fab fa-linkedin-in h-5 w-5"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Navigasi</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-slate-400 hover:text-white">Fitur</a></li>
                        <li><a href="#testimonials" class="text-slate-400 hover:text-white">Testimoni</a></li>
                        <li><a href="#statistics" class="text-slate-400 hover:text-white">Statistik</a></li>
                        <li><a href="#faq" class="text-slate-400 hover:text-white">FAQ</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white">Data Alumni</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white">Login</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white">Register</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Website Terkait</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-slate-400 hover:text-white">Website Resmi PTIK</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white">Portal Akademik</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white">Bursa Kerja Khusus</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white">Penelitian Dosen</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white">Jurnal Ilmiah</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Kontak</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt h-5 w-5 mr-3 text-primary-400 flex-shrink-0 mt-1"></i>
                            <span class="text-slate-400">Jl. Raya Bogor Km. 28, Jakarta Timur, Indonesia</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-envelope h-5 w-5 mr-3 text-primary-400 flex-shrink-0 mt-1"></i>
                            <span class="text-slate-400">alumni@ptikipb.ac.id</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone h-5 w-5 mr-3 text-primary-400 flex-shrink-0 mt-1"></i>
                            <span class="text-slate-400">+62 812-3456-7890</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-slate-500 text-sm mb-4 md:mb-0">© {{ date('Y') }} PTIK Institut Prima Bangsa. Hak Cipta Dilindungi.</p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-slate-500 hover:text-white text-sm">Kebijakan Privasi</a>
                        <a href="#" class="text-slate-500 hover:text-white text-sm">Syarat & Ketentuan</a>
                        <a href="#" class="text-slate-500 hover:text-white text-sm">Peta Situs</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
            
            // Close mobile menu when clicking links
            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                });
            });
        }
        
        // FAQ accordion
        const faqToggles = document.querySelectorAll('.faq-toggle');
        
        faqToggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                const content = toggle.nextElementSibling;
                const icon = toggle.querySelector('.faq-icon');
                
                content.classList.toggle('hidden');
                if (icon) {
                    icon.classList.toggle('rotate-180');
                }
            });
        });
        
        // Create animated particles
        createParticles();
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // Close mobile menu if open
                    if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                    }
                }
            });
        });
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
            const size = Math.random() * 6 + 2;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            // Random position
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`;
            
            // Random opacity
            particle.style.opacity = Math.random() * 0.5 + 0.1;
            
            // Random animation
            const duration = Math.random() * 20 + 10;
            particle.style.animation = `float ${duration}s infinite ease-in-out`;
            particle.style.animationDelay = `${Math.random() * 5}s`;
            
            container.appendChild(particle);
        }
    }
</script>
@endsection