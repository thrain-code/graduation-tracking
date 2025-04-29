<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Statistik Alumni - Institut Prima Bangsa</title>

  <!-- Font untuk meningkatkan performa -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap"
    rel="stylesheet">

  <!-- Font Icons dengan preload untuk kecepatan -->
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <!-- Memuat Chart.js dengan versi yang stabil -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

  <!-- Load tailwind -->
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

  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      color: #f8fafc;
      scroll-behavior: smooth;
    }

    .floating {
      animation: float 6s ease-in-out infinite;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0) rotate(0);
      }

      50% {
        transform: translateY(-10px) rotate(1deg);
      }
    }

    /* Skeleton loader untuk konten */
    .skeleton {
      background: linear-gradient(90deg, rgba(255, 255, 255, 0.05) 25%, rgba(255, 255, 255, 0.1) 50%, rgba(255, 255, 255, 0.05) 75%);
      background-size: 200% 100%;
      animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
      0% {
        background-position: -200% 0;
      }

      100% {
        background-position: 200% 0;
      }
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    ::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    /* Particle background */
    .particle {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="fixed top-0 left-0 w-full backdrop-blur-lg bg-slate-900/80 z-50 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex-shrink-0">
          <a href="#" class="flex items-center">
            <img src="{{ asset('assets/logo.ico') }}" alt="Institut Prima Bangsa Logo" class="h-10 w-auto mr-3">
            <span class="text-white font-bold text-xl">Institut Prima Bangsa</span>
          </a>
        </div>
        <div class="hidden md:block">
          <div class="flex items-baseline space-x-4">
            <a href="#beranda"
              class="text-white hover:bg-slate-800 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Beranda</a>
            <a href="#statistik"
              class="text-gray-300 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Statistik</a>
            <a href="#tren"
              class="text-gray-300 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Tren
              Alumni</a>
            <a href="#sektor"
              class="text-gray-300 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Sektor
              Kerja</a>
          </div>
        </div>
        <div class="-mr-2 flex md:hidden">
          <button id="mobileMenuButton" type="button"
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
            <i class="fas fa-bars h-6 w-6"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div id="mobileMenu" class="hidden md:hidden">
      <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
        <a href="#beranda" class="text-white block px-3 py-2 rounded-md text-base font-medium">Beranda</a>
        <a href="#statistik" class="text-gray-300 block px-3 py-2 rounded-md text-base font-medium">Statistik</a>
        <a href="#tren" class="text-gray-300 block px-3 py-2 rounded-md text-base font-medium">Tren Alumni</a>
        <a href="#sektor" class="text-gray-300 block px-3 py-2 rounded-md text-base font-medium">Sektor Kerja</a>
        <a href="#perusahaan" class="text-gray-300 block px-3 py-2 rounded-md text-base font-medium">Top Perusahaan</a>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <section id="beranda"
    class="min-h-screen flex flex-col items-center justify-center text-center px-6 relative overflow-hidden pt-16">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-900/30 via-purple-800/20 to-transparent blur-3xl z-0"></div>

    <!-- Animated particles background -->
    <div id="particles" class="absolute inset-0 z-0"></div>

    <div class="z-10 max-w-3xl">
      <span class="bg-primary-600 text-white text-xs font-semibold px-3 py-1 rounded-full inline-block mb-4">STATISTIK
        ALUMNI</span>
      <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-6 text-white drop-shadow-lg">
        Perjalanan Karir Alumni <span class="text-primary-400">Institut Prima Bangsa</span>
      </h1>
      <p class="text-lg md:text-xl text-slate-200 mb-8 leading-relaxed">
        Visualisasi data alumni IPB dari tahun 2019-2024 mencakup distribusi karir, studi lanjut, dan perkembangan
        profesional.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="#statistik"
          class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition transform hover:scale-105 shadow-lg">
          Lihat Statistik
        </a>
      </div>
    </div>

    <!-- Floating illustrations -->
    <div class="absolute right-10 bottom-20 hidden lg:block z-0 opacity-60 floating">
      <i class="fas fa-user-graduate text-6xl text-primary-400"></i>
    </div>
    <div class="absolute left-10 top-40 hidden lg:block z-0 opacity-60 floating">
      <i class="fas fa-university text-4xl text-blue-400"></i>
    </div>
  </section>

  <!-- Statistik Cards -->
  <section id="statistik" class="py-20 px-6 relative">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-white mb-4">Statistik Alumni</h2>
        <p class="text-slate-300 max-w-2xl mx-auto">Persentase keberhasilan lulusan Institut Prima Bangsa dalam memasuki
          dunia kerja, pendidikan lanjut, dan pencapaian lainnya.</p>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <div
          class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-6 shadow-xl border border-slate-700/30 hover:transform hover:scale-105 transition duration-300">
          <div class="flex items-center justify-center w-16 h-16 rounded-xl bg-green-500/20 text-green-400 mb-4">
            <i class="fas fa-briefcase text-3xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-green-400 mb-1">82%</h3>
          <p class="text-slate-300 mb-3 font-medium">Sudah Bekerja</p>
          <p class="text-slate-400 text-sm">Bekerja di berbagai sektor industri dalam waktu kurang dari 6 bulan setelah
            lulus.</p>
          <div class="w-full bg-slate-700/30 h-1.5 rounded-full mt-4 overflow-hidden">
            <div class="bg-green-400 h-full rounded-full" style="width: 82%"></div>
          </div>
        </div>

        <div
          class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-6 shadow-xl border border-slate-700/30 hover:transform hover:scale-105 transition duration-300">
          <div class="flex items-center justify-center w-16 h-16 rounded-xl bg-blue-500/20 text-blue-400 mb-4">
            <i class="fas fa-graduation-cap text-3xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-blue-400 mb-1">12%</h3>
          <p class="text-slate-300 mb-3 font-medium">Lanjut Studi</p>
          <p class="text-slate-400 text-sm">Melanjutkan pendidikan ke jenjang S2 atau program studi lanjutan lainnya.
          </p>
          <div class="w-full bg-slate-700/30 h-1.5 rounded-full mt-4 overflow-hidden">
            <div class="bg-blue-400 h-full rounded-full" style="width: 12%"></div>
          </div>
        </div>

        <div
          class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-6 shadow-xl border border-slate-700/30 hover:transform hover:scale-105 transition duration-300">
          <div class="flex items-center justify-center w-16 h-16 rounded-xl bg-amber-500/20 text-amber-400 mb-4">
            <i class="fas fa-search text-3xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-amber-400 mb-1">6%</h3>
          <p class="text-slate-300 mb-3 font-medium">Mencari Kerja</p>
          <p class="text-slate-400 text-sm">Dalam proses mencari pekerjaan atau menyelesaikan program sertifikasi.</p>
          <div class="w-full bg-slate-700/30 h-1.5 rounded-full mt-4 overflow-hidden">
            <div class="bg-amber-400 h-full rounded-full" style="width: 6%"></div>
          </div>
        </div>
      </div>

      <!-- Extra Statistics Card -->
      <div class="grid md:grid-cols-2 gap-6 mt-6">
        <div
          class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-6 shadow-xl border border-slate-700/30">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-white">Waktu Tunggu Mendapat Kerja</h3>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-500/20 text-purple-400">
              <i class="fas fa-clock text-lg"></i>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-4">
            <div class="text-center p-3 rounded-lg bg-white/5">
              <p class="text-2xl font-bold text-purple-400">68%</p>
              <p class="text-slate-300 text-sm">
                < 3 bulan</p>
            </div>
            <div class="text-center p-3 rounded-lg bg-white/5">
              <p class="text-2xl font-bold text-purple-400">25%</p>
              <p class="text-slate-300 text-sm">3-6 bulan</p>
            </div>
            <div class="text-center p-3 rounded-lg bg-white/5">
              <p class="text-2xl font-bold text-purple-400">7%</p>
              <p class="text-slate-300 text-sm">> 6 bulan</p>
            </div>
          </div>
        </div>

        <div
          class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-6 shadow-xl border border-slate-700/30">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-white">Kesesuaian Pekerjaan</h3>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-teal-500/20 text-teal-400">
              <i class="fas fa-check-circle text-lg"></i>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-4">
            <div class="text-center p-3 rounded-lg bg-white/5">
              <p class="text-2xl font-bold text-teal-400">75%</p>
              <p class="text-slate-300 text-sm">Sangat Sesuai</p>
            </div>
            <div class="text-center p-3 rounded-lg bg-white/5">
              <p class="text-2xl font-bold text-teal-400">20%</p>
              <p class="text-slate-300 text-sm">Cukup Sesuai</p>
            </div>
            <div class="text-center p-3 rounded-lg bg-white/5">
              <p class="text-2xl font-bold text-teal-400">5%</p>
              <p class="text-slate-300 text-sm">Kurang Sesuai</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Grafik Alumni -->
  <section id="tren" class="py-20 px-6 bg-slate-900/50">
    <div class="max-w-5xl mx-auto mb-14 text-center">
      <h2 class="text-3xl font-bold text-white mb-4">Perkembangan Status Alumni</h2>
      <p class="text-slate-300 max-w-2xl mx-auto">Tren status alumni Institut Prima Bangsa dari tahun 2019 hingga 2024
        menunjukkan peningkatan persentase alumni yang bekerja.</p>
    </div>

    <div
      class="max-w-5xl mx-auto bg-gradient-to-br from-slate-800/60 to-slate-900/60 backdrop-blur-xl rounded-2xl p-8 shadow-xl border border-slate-700/30">
      <div class="flex flex-wrap justify-center gap-4 mb-6">
        <button
          class="chart-filter px-4 py-2 rounded-lg bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 border border-primary-500/20 active"
          data-chart="status">Status Alumni</button>
        <button
          class="chart-filter px-4 py-2 rounded-lg bg-white/10 text-white text-sm font-medium hover:bg-white/20 border border-white/5"
          data-chart="salary">Rentang Gaji</button>
        <button
          class="chart-filter px-4 py-2 rounded-lg bg-white/10 text-white text-sm font-medium hover:bg-white/20 border border-white/5"
          data-chart="companies">Perusahaan</button>
      </div>

      <div class="relative">
        <canvas id="alumniChart" class="w-full" style="height: 380px;"></canvas>
        <div id="chartLoader"
          class="absolute inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm rounded-lg">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Sektor Kerja -->
  <section id="sektor" class="py-20 px-6">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-white mb-4">Sektor Pekerjaan Alumni</h2>
        <p class="text-slate-300 max-w-2xl mx-auto">Distribusi alumni berdasarkan sektor industri tempat mereka
          berkarir.</p>
      </div>

      <div class="grid md:grid-cols-2 gap-8">
        <div
          class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-6 shadow-xl border border-slate-700/30 flex flex-col justify-center">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-white">Distribusi Sektor (2024)</h3>
            <select id="yearSelect"
              class="bg-slate-800 text-white border border-slate-700 rounded-lg px-3 py-1 text-sm">
              <option value="2024" selected>2024</option>
              <option value="2023">2023</option>
              <option value="2022">2022</option>
              <option value="2021">2021</option>
              <option value="2020">2020</option>
              <option value="2019">2019</option>
            </select>
          </div>
          <div style="height: 320px; width: 100%; position: relative;">
            <canvas id="sectorChart"></canvas>
          </div>
        </div>

        <div class="space-y-4">
          <div
            class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-4 shadow-xl border border-slate-700/30 flex items-center">
            <div class="w-3 h-10 bg-blue-500 rounded-full mr-4"></div>
            <div class="flex-1">
              <h4 class="font-semibold text-white">Teknologi Informasi</h4>
              <p class="text-slate-400 text-sm">54% alumni bekerja di perusahaan teknologi</p>
            </div>
            <div class="text-2xl font-bold text-blue-400">54%</div>
          </div>

          <div
            class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-4 shadow-xl border border-slate-700/30 flex items-center">
            <div class="w-3 h-10 bg-purple-500 rounded-full mr-4"></div>
            <div class="flex-1">
              <h4 class="font-semibold text-white">Pendidikan</h4>
              <p class="text-slate-400 text-sm">15% alumni bekerja di institusi pendidikan</p>
            </div>
            <div class="text-2xl font-bold text-purple-400">15%</div>
          </div>

          <div
            class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-4 shadow-xl border border-slate-700/30 flex items-center">
            <div class="w-3 h-10 bg-green-500 rounded-full mr-4"></div>
            <div class="flex-1">
              <h4 class="font-semibold text-white">Finansial & Perbankan</h4>
              <p class="text-slate-400 text-sm">12% alumni bekerja di sektor keuangan</p>
            </div>
            <div class="text-2xl font-bold text-green-400">12%</div>
          </div>

          <div
            class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-4 shadow-xl border border-slate-700/30 flex items-center">
            <div class="w-3 h-10 bg-amber-500 rounded-full mr-4"></div>
            <div class="flex-1">
              <h4 class="font-semibold text-white">E-Commerce</h4>
              <p class="text-slate-400 text-sm">14% alumni bekerja di perusahaan e-commerce</p>
            </div>
            <div class="text-2xl font-bold text-amber-400">14%</div>
          </div>

          <div
            class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-4 shadow-xl border border-slate-700/30 flex items-center">
            <div class="w-3 h-10 bg-red-500 rounded-full mr-4"></div>
            <div class="flex-1">
              <h4 class="font-semibold text-white">Lainnya</h4>
              <p class="text-slate-400 text-sm">5% alumni bekerja di sektor lainnya</p>
            </div>
            <div class="text-2xl font-bold text-red-400">5%</div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Footer -->
  <footer class="py-16 px-6 bg-slate-900">
    <div class="max-w-6xl mx-auto">
      <div class="grid md:grid-cols-4 gap-8 mb-12">
        <div>
          <div class="flex items-center mb-6">
            <i class="fas fa-university text-4xl text-primary-500 mr-3"></i>
            <h3 class="text-xl font-bold text-white">Institut Prima Bangsa</h3>
          </div>
          <p class="text-slate-400 mb-6">Platform resmi untuk pelacakan alumni Institut Prima Bangsa. Dapatkan informasi
            terkini tentang perkembangan karier alumni.</p>
          <div class="flex space-x-4">
            <a href="#" class="text-slate-400 hover:text-white">
              <i class="fab fa-facebook-f h-6 w-6"></i>
            </a>
            <a href="#" class="text-slate-400 hover:text-white">
              <i class="fab fa-twitter h-6 w-6"></i>
            </a>
            <a href="#" class="text-slate-400 hover:text-white">
              <i class="fab fa-instagram h-6 w-6"></i>
            </a>
            <a href="#" class="text-slate-400 hover:text-white">
              <i class="fab fa-linkedin-in h-6 w-6"></i>
            </a>
          </div>
        </div>

        <div>
          <h4 class="text-lg font-semibold text-white mb-4">Link Cepat</h4>
          <ul class="space-y-2">
            <li><a href="#beranda" class="text-slate-400 hover:text-white">Beranda</a></li>
            <li><a href="#statistik" class="text-slate-400 hover:text-white">Statistik</a></li>
            <li><a href="#tren" class="text-slate-400 hover:text-white">Tren Alumni</a></li>
            <li><a href="#sektor" class="text-slate-400 hover:text-white">Sektor Kerja</a></li>
            <li><a href="#perusahaan" class="text-slate-400 hover:text-white">Top Perusahaan</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-lg font-semibold text-white mb-4">Website Terkait</h4>
          <ul class="space-y-2">
            <li><a href="#" class="text-slate-400 hover:text-white">Website Resmi Institut</a></li>
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
              <span class="text-slate-400">Jl. Raya Pendidikan No. 28, Jakarta Timur, Indonesia</span>
            </li>
            <li class="flex items-start">
              <i class="fas fa-envelope h-5 w-5 mr-3 text-primary-400 flex-shrink-0 mt-1"></i>
              <span class="text-slate-400">info@primabangsa.ac.id</span>
            </li>
            <li class="flex items-start">
              <i class="fas fa-phone h-5 w-5 mr-3 text-primary-400 flex-shrink-0 mt-1"></i>
              <span class="text-slate-400">+62 21-1234-5678</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="border-t border-slate-800 pt-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <p class="text-slate-500 text-sm mb-4 md:mb-0">© 2025 Institut Prima Bangsa. Hak Cipta Dilindungi.</p>
          <div class="flex space-x-6">
            <a href="#" class="text-slate-500 hover:text-white text-sm">Kebijakan Privasi</a>
            <a href="#" class="text-slate-500 hover:text-white text-sm">Syarat & Ketentuan</a>
            <a href="#" class="text-slate-500 hover:text-white text-sm">Peta Situs</a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Back to top button -->
  <button id="backToTop"
    class="fixed bottom-6 right-6 bg-primary-600 hover:bg-primary-700 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg transform transition hover:scale-110 opacity-0 pointer-events-none">
    <i class="fas fa-arrow-up"></i>
  </button>

  <!-- Scripts -->
  <script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileMenuButton && mobileMenu) {
      mobileMenuButton.addEventListener('click', function () {
        if (mobileMenu.classList.contains('hidden')) {
          mobileMenu.classList.remove('hidden');
        } else {
          mobileMenu.classList.add('hidden');
        }
      });

      // Close menu when clicking links
      document.querySelectorAll('#mobileMenu a').forEach(function (link) {
        link.addEventListener('click', function () {
          mobileMenu.classList.add('hidden');
        });
      });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          window.scrollTo({
            top: target.offsetTop - 70,
            behavior: 'smooth'
          });

          // Close mobile menu if open
          if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
          }
        }
      });
    });

    // Back to top button
    const backToTopButton = document.getElementById('backToTop');

    if (backToTopButton) {
      window.addEventListener('scroll', () => {
        if (window.scrollY > 500) {
          backToTopButton.classList.remove('opacity-0');
          backToTopButton.classList.add('opacity-100');
          backToTopButton.classList.remove('pointer-events-none');
          backToTopButton.classList.add('pointer-events-auto');
        } else {
          backToTopButton.classList.remove('opacity-100');
          backToTopButton.classList.add('opacity-0');
          backToTopButton.classList.remove('pointer-events-auto');
          backToTopButton.classList.add('pointer-events-none');
        }
      });

      backToTopButton.addEventListener('click', () => {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
    }

    // Animated particles background
    function createParticles() {
      const particlesContainer = document.getElementById('particles');
      if (!particlesContainer) return;

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

        particlesContainer.appendChild(particle);
      }
    }

    // Create particles on page load
    document.addEventListener('DOMContentLoaded', createParticles);

    // Charts
    document.addEventListener('DOMContentLoaded', function () {
      // Hide chart loader after charts are initialized
      setTimeout(() => {
        const chartLoader = document.getElementById('chartLoader');
        if (chartLoader) {
          chartLoader.style.display = 'none';
        }
      }, 1000);

      // Alumni Chart
      const alumniChartCtx = document.getElementById('alumniChart');
      if (alumniChartCtx) {
        const alumniChart = new Chart(alumniChartCtx, {
          type: 'line',
          data: {
            labels: ['2019', '2020', '2021', '2022', '2023', '2024'],
            datasets: [
              {
                label: 'Bekerja',
                data: [72, 75, 78, 79, 80, 82],
                borderColor: '#22C55E',
                backgroundColor: 'rgba(34,197,94,0.1)',
                tension: 0.4,
                fill: true
              },
              {
                label: 'Lanjut Studi',
                data: [18, 16, 15, 14, 13, 12],
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                tension: 0.4,
                fill: true
              },
              {
                label: 'Mencari Kerja',
                data: [10, 9, 7, 7, 7, 6],
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245,158,11,0.1)',
                tension: 0.4,
                fill: true
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  color: '#fff',
                  usePointStyle: true,
                  padding: 20
                }
              },
              tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.8)',
                titleColor: '#fff',
                bodyColor: '#cbd5e1',
                padding: 12,
                borderColor: 'rgba(148, 163, 184, 0.2)',
                borderWidth: 1,
                displayColors: true,
                usePointStyle: true
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  color: '#94a3b8',
                  font: {
                    size: 11
                  }
                },
                grid: {
                  color: 'rgba(148, 163, 184, 0.1)',
                  drawBorder: false
                },
                title: {
                  display: true,
                  text: 'Persentase (%)',
                  color: '#cbd5e1',
                  font: {
                    size: 12
                  }
                }
              },
              x: {
                ticks: {
                  color: '#94a3b8',
                  font: {
                    size: 11
                  }
                },
                grid: {
                  display: false,
                  drawBorder: false
                },
                title: {
                  display: true,
                  text: 'Tahun Lulusan',
                  color: '#cbd5e1',
                  font: {
                    size: 12
                  }
                }
              }
            },
            elements: {
              point: {
                radius: 3,
                hoverRadius: 5
              }
            }
          }
        });

        // Chart filters
        const chartFilters = document.querySelectorAll('.chart-filter');
        chartFilters.forEach(filter => {
          filter.addEventListener('click', () => {
            // Remove active class from all filters
            chartFilters.forEach(f => {
              f.classList.remove('active', 'bg-primary-600');
              f.classList.add('bg-white/10');
            });

            // Add active class to current filter
            filter.classList.remove('bg-white/10');
            filter.classList.add('active', 'bg-primary-600');

            // Update chart data based on filter
            const chartType = filter.getAttribute('data-chart');

            if (chartType === 'status') {
              alumniChart.data.datasets = [
                {
                  label: 'Bekerja',
                  data: [72, 75, 78, 79, 80, 82],
                  borderColor: '#22C55E',
                  backgroundColor: 'rgba(34,197,94,0.1)',
                  tension: 0.4,
                  fill: true
                },
                {
                  label: 'Lanjut Studi',
                  data: [18, 16, 15, 14, 13, 12],
                  borderColor: '#3B82F6',
                  backgroundColor: 'rgba(59,130,246,0.1)',
                  tension: 0.4,
                  fill: true
                },
                {
                  label: 'Mencari Kerja',
                  data: [10, 9, 7, 7, 7, 6],
                  borderColor: '#F59E0B',
                  backgroundColor: 'rgba(245,158,11,0.1)',
                  tension: 0.4,
                  fill: true
                }
              ];
            } else if (chartType === 'salary') {
              alumniChart.data.datasets = [
                {
                  label: '< 8 juta',
                  data: [60, 55, 48, 40, 35, 30],
                  borderColor: '#f43f5e',
                  backgroundColor: 'rgba(244,63,94,0.1)',
                  tension: 0.4,
                  fill: true
                },
                {
                  label: '8-12 juta',
                  data: [30, 32, 35, 40, 42, 45],
                  borderColor: '#8b5cf6',
                  backgroundColor: 'rgba(139,92,246,0.1)',
                  tension: 0.4,
                  fill: true
                },
                {
                  label: '> 12 juta',
                  data: [10, 13, 17, 20, 23, 25],
                  borderColor: '#10b981',
                  backgroundColor: 'rgba(16,185,129,0.1)',
                  tension: 0.4,
                  fill: true
                }
              ];
            } else if (chartType === 'companies') {
              alumniChart.data.datasets = [
                {
                  label: 'Teknologi',
                  data: [42, 45, 48, 50, 52, 54],
                  borderColor: '#0ea5e9',
                  backgroundColor: 'rgba(14,165,233,0.1)',
                  tension: 0.4,
                  fill: true
                },
                {
                  label: 'Pendidikan',
                  data: [25, 23, 20, 18, 17, 15],
                  borderColor: '#ec4899',
                  backgroundColor: 'rgba(236,72,153,0.1)',
                  tension: 0.4,
                  fill: true
                },
                {
                  label: 'Perbankan',
                  data: [18, 17, 16, 15, 14, 12],
                  borderColor: '#eab308',
                  backgroundColor: 'rgba(234,179,8,0.1)',
                  tension: 0.4,
                  fill: true
                },
                {
                  label: 'E-Commerce',
                  data: [9, 10, 11, 12, 13, 14],
                  borderColor: '#14b8a6',
                  backgroundColor: 'rgba(20,184,166,0.1)',
                  tension: 0.4,
                  fill: true
                },
                {
                  label: 'Lainnya',
                  data: [6, 5, 5, 5, 4, 5],
                  borderColor: '#ef4444',
                  backgroundColor: 'rgba(239,68,68,0.1)',
                  tension: 0.4,
                  fill: true
                }
              ];
            }

            alumniChart.update();
          });
        });
      }

      // Sector Chart
      const sectorChartCtx = document.getElementById('sectorChart');
      if (sectorChartCtx) {
        // Data for different years
        const sectorData = {
          2019: {
            labels: ['Teknologi Informasi', 'Pendidikan', 'Finansial & Perbankan', 'E-Commerce', 'Lainnya'],
            data: [42, 25, 18, 9, 6]
          },
          2020: {
            labels: ['Teknologi Informasi', 'Pendidikan', 'Finansial & Perbankan', 'E-Commerce', 'Lainnya'],
            data: [45, 23, 17, 10, 5]
          },
          2021: {
            labels: ['Teknologi Informasi', 'Pendidikan', 'Finansial & Perbankan', 'E-Commerce', 'Lainnya'],
            data: [48, 20, 16, 11, 5]
          },
          2022: {
            labels: ['Teknologi Informasi', 'Pendidikan', 'Finansial & Perbankan', 'E-Commerce', 'Lainnya'],
            data: [50, 18, 15, 12, 5]
          },
          2023: {
            labels: ['Teknologi Informasi', 'Pendidikan', 'Finansial & Perbankan', 'E-Commerce', 'Lainnya'],
            data: [52, 17, 14, 13, 4]
          },
          2024: {
            labels: ['Teknologi Informasi', 'Pendidikan', 'Finansial & Perbankan', 'E-Commerce', 'Lainnya'],
            data: [54, 15, 12, 14, 5]
          }
        };

        const sectorChart = new Chart(sectorChartCtx, {
          type: 'doughnut',
          data: {
            labels: sectorData[2024].labels,
            datasets: [{
              data: sectorData[2024].data,
              backgroundColor: [
                '#3b82f6',
                '#a855f7',
                '#22c55e',
                '#f59e0b',
                '#ef4444'
              ],
              borderColor: 'rgba(15, 23, 42, 0.5)',
              borderWidth: 1,
              hoverOffset: 5
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  color: '#fff',
                  usePointStyle: true,
                  padding: 20,
                  font: {
                    size: 11
                  }
                }
              },
              tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.8)',
                titleColor: '#fff',
                bodyColor: '#cbd5e1',
                padding: 12,
                borderColor: 'rgba(148, 163, 184, 0.2)',
                borderWidth: 1,
                displayColors: true,
                usePointStyle: true,
                callbacks: {
                  label: function (context) {
                    return context.label + ': ' + context.raw + '%';
                  }
                }
              }
            },
            cutout: '65%'
          }
        });

        // Year select change handler
        const yearSelect = document.getElementById('yearSelect');
        if (yearSelect) {
          yearSelect.addEventListener('change', function () {
            const year = this.value;

            // Update chart title
            const title = document.querySelector('#sektor h3');
            if (title) {
              title.textContent = `Distribusi Sektor (${year})`;
            }

            // Update chart data
            sectorChart.data.labels = sectorData[year].labels;
            sectorChart.data.datasets[0].data = sectorData[year].data;
            sectorChart.update();

            // Update sector percentages in the right panel
            const sectorItems = document.querySelectorAll('#sektor .space-y-4 .backdrop-blur-xl');
            sectorItems.forEach((item, index) => {
              if (index < sectorData[year].labels.length) {
                const percentText = item.querySelector('.text-2xl');
                const descText = item.querySelector('.text-slate-400.text-sm');

                if (percentText) {
                  percentText.textContent = `${sectorData[year].data[index]}%`;
                }

                if (descText) {
                  const sectorName = sectorData[year].labels[index].toLowerCase();
                  const sectorPercent = sectorData[year].data[index];
                  descText.textContent = `${sectorPercent}% alumni bekerja di ${sectorName === 'lainnya' ? 'sektor lainnya' : (sectorName.includes('&') ? 'sektor ' + sectorName : sectorName)}`;
                }
              }
            });
          });
        }
      }
    });
  </script>
</body>

</html>