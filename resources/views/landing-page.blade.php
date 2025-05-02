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
  <link rel="icon" href="{{ asset('assets/logo.ico') }}" type="image/x-icon">

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
            <a href="#jenis-pekerjaan"
              class="text-gray-300 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Jenis
              Pekerjaan</a>
            <a href="#per-prodi"
              class="text-gray-300 hover:bg-slate-800 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Per Prodi</a>
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
        <a href="#jenis-pekerjaan" class="text-gray-300 block px-3 py-2 rounded-md text-base font-medium">Jenis Pekerjaan</a>
        <a href="#per-prodi" class="text-gray-300 block px-3 py-2 rounded-md text-base font-medium">Per Prodi</a>
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
        @if(count($alumni) > 0)
        Visualisasi data {{ count($alumni) }} alumni IPB dari berbagai tahun kelulusan mencakup distribusi karir, studi lanjut, dan perkembangan
        profesional.
        @else
        Belum ada data alumni yang tersedia saat ini. Silakan kembali lagi nanti.
        @endif
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="#statistik"
          class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition transform hover:scale-105 shadow-lg">
          Lihat Statistik
        </a>
        <a href="{{ route('login.form') }}"
          class="bg-white/10 hover:bg-white/20 text-white font-semibold py-3 px-6 rounded-lg transition transform hover:scale-105 shadow-lg">
          Login Alumni
        </a>
      </div>
    </div>

  </section>

  <!-- Statistik Cards -->
  <section id="statistik" class="py-20 px-6 relative">
    <div class="max-w-6xl mx-auto">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-white mb-4">Statistik Alumni</h2>
        <p class="text-slate-300 max-w-2xl mx-auto">
          @if(count($alumni) > 0)
          Persentase keberhasilan lulusan Institut Prima Bangsa dalam memasuki dunia kerja, pendidikan lanjut, dan pencapaian lainnya.
          @else
          Belum ada data statistik alumni yang tersedia saat ini.
          @endif
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
          class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-6 shadow-xl border border-slate-700/30 hover:transform hover:scale-105 transition duration-300">
          <div class="flex items-center justify-center w-16 h-16 rounded-xl bg-green-500/20 text-green-400 mb-4">
            <i class="fas fa-briefcase text-3xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-green-400 mb-1">{{ $stats['bekerja_percent'] }}%</h3>
          <p class="text-slate-300 mb-3 font-medium">Sudah Bekerja</p>
          <p class="text-slate-400 text-sm">Bekerja di berbagai sektor industri dalam waktu kurang dari 6 bulan setelah
            lulus.</p>
          <div class="w-full bg-slate-700/30 h-1.5 rounded-full mt-4 overflow-hidden">
            <div class="bg-green-400 h-full rounded-full" style="width: {{ $stats['bekerja_percent'] }}%"></div>
          </div>
        </div>

        <div
          class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-6 shadow-xl border border-slate-700/30 hover:transform hover:scale-105 transition duration-300">
          <div class="flex items-center justify-center w-16 h-16 rounded-xl bg-blue-500/20 text-blue-400 mb-4">
            <i class="fas fa-graduation-cap text-3xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-blue-400 mb-1">{{ $stats['studi_lanjut_percent'] }}%</h3>
          <p class="text-slate-300 mb-3 font-medium">Lanjut Studi</p>
          <p class="text-slate-400 text-sm">Melanjutkan pendidikan ke jenjang S2 atau program studi lanjutan lainnya.
          </p>
          <div class="w-full bg-slate-700/30 h-1.5 rounded-full mt-4 overflow-hidden">
            <div class="bg-blue-400 h-full rounded-full" style="width: {{ $stats['studi_lanjut_percent'] }}%"></div>
          </div>
        </div>

        <div
          class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-6 shadow-xl border border-slate-700/30 hover:transform hover:scale-105 transition duration-300">
          <div class="flex items-center justify-center w-16 h-16 rounded-xl bg-orange-500/20 text-orange-400 mb-4">
            <i class="fas fa-store text-3xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-orange-400 mb-1">{{ $stats['wirausaha_percent'] }}%</h3>
          <p class="text-slate-300 mb-3 font-medium">Wirausaha</p>
          <p class="text-slate-400 text-sm">Menjalankan usaha sendiri atau berwirausaha setelah lulus.
          </p>
          <div class="w-full bg-slate-700/30 h-1.5 rounded-full mt-4 overflow-hidden">
            <div class="bg-orange-400 h-full rounded-full" style="width: {{ $stats['wirausaha_percent'] }}%"></div>
          </div>
        </div>

        <div
          class="backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-6 shadow-xl border border-slate-700/30 hover:transform hover:scale-105 transition duration-300">
          <div class="flex items-center justify-center w-16 h-16 rounded-xl bg-purple-500/20 text-purple-400 mb-4">
            <i class="fas fa-home text-3xl"></i>
          </div>
          <h3 class="text-3xl font-bold text-purple-400 mb-1">{{ $stats['mengurus_keluarga_percent'] }}%</h3>
          <p class="text-slate-300 mb-3 font-medium">Mengurus Keluarga</p>
          <p class="text-slate-400 text-sm">Memilih untuk fokus mengurus keluarga setelah lulus.
          </p>
          <div class="w-full bg-slate-700/30 h-1.5 rounded-full mt-4 overflow-hidden">
            <div class="bg-purple-400 h-full rounded-full" style="width: {{ $stats['mengurus_keluarga_percent'] }}%"></div>
          </div>
        </div>
      </div>

      <!-- Pie Chart Section -->
      <div class="mt-12 grid md:grid-cols-3 gap-6">
        <div class="md:col-span-1 backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-6 shadow-xl border border-slate-700/30">
          <h3 class="text-xl font-bold text-white mb-4 text-center">Distribusi Status Alumni</h3>
          <div class="relative">
            <canvas id="statusPieChart" class="max-w-full mx-auto" height="280"></canvas>

            <!-- Fallback message when no data is available -->
            @if(count($alumni) == 0 || ($stats['bekerja_count'] + $stats['studi_lanjut_count'] + $stats['wirausaha_count'] + $stats['mengurus_keluarga_count'] == 0))
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="text-slate-400 text-center">
                <i class="fas fa-chart-pie text-3xl mb-3 opacity-30"></i>
                <p>Belum ada data</p>
              </div>
            </div>
            @endif
          </div>
        </div>

        <div class="md:col-span-2 backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-6 shadow-xl border border-slate-700/30">
          <h3 class="text-xl font-bold text-white mb-4">Informasi Tambahan</h3>

          <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-slate-800/50 rounded-lg p-4">
              <div class="text-center mb-2">
                <span class="text-primary-400 text-2xl font-bold">{{ count($alumni) }}</span>
              </div>
              <p class="text-slate-300 text-center text-sm">Total Alumni</p>
            </div>

            <div class="bg-slate-800/50 rounded-lg p-4">
              <div class="text-center mb-2">
                <span class="text-green-400 text-2xl font-bold">{{ $stats['bekerja_count'] }}</span>
              </div>
              <p class="text-slate-300 text-center text-sm">Alumni Bekerja</p>
            </div>

            <div class="bg-slate-800/50 rounded-lg p-4">
              <div class="text-center mb-2">
                <span class="text-blue-400 text-2xl font-bold">{{ $stats['studi_lanjut_count'] }}</span>
              </div>
              <p class="text-slate-300 text-center text-sm">Alumni Studi Lanjut</p>
            </div>

            <div class="bg-slate-800/50 rounded-lg p-4">
              <div class="text-center mb-2">
                <span class="text-orange-400 text-2xl font-bold">{{ $stats['wirausaha_count'] }}</span>
              </div>
              <p class="text-slate-300 text-center text-sm">Alumni Wirausaha</p>
            </div>

            <div class="bg-slate-800/50 rounded-lg p-4">
              <div class="text-center mb-2">
                <span class="text-purple-400 text-2xl font-bold">{{ $stats['mengurus_keluarga_count'] }}</span>
              </div>
              <p class="text-slate-300 text-center text-sm">Mengurus Keluarga</p>
            </div>

            <div class="bg-slate-800/50 rounded-lg p-4">
              <div class="text-center mb-2">
                @php
                  $currentYear = date('Y');
                  $recentGrads = $alumni->where('tahun_lulus', '>=', $currentYear - 1)->count();
                @endphp
                <span class="text-yellow-400 text-2xl font-bold">{{ $recentGrads }}</span>
              </div>
              <p class="text-slate-300 text-center text-sm">Lulusan 1 Tahun Terakhir</p>
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
      <p class="text-slate-300 max-w-2xl mx-auto">
        @if(count($alumni) > 0)
        Tren status alumni Institut Prima Bangsa dari tahun {{ min(array_keys($stats['yearly_data'] ?? [2019 => true])) }} hingga terkini menunjukkan persentase alumni berdasarkan status.
        @else
        Belum ada data tren alumni yang tersedia saat ini.
        @endif
      </p>
    </div>

    <div
      class="max-w-5xl mx-auto bg-gradient-to-br from-slate-800/60 to-slate-900/60 backdrop-blur-xl rounded-2xl p-8 shadow-xl border border-slate-700/30">
      <div class="flex flex-wrap justify-center gap-4 mb-6">
        <button
          class="chart-filter px-4 py-2 rounded-lg bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 border border-primary-500/20 active"
          data-chart="status">Status Alumni</button>
      </div>

      <div class="relative">
        @if(count($alumni) > 0)
        <canvas id="alumniChart" class="w-full" style="height: 380px;"></canvas>
        @else
        <div class="flex items-center justify-center h-80">
          <div class="text-slate-400 text-center">
            <i class="fas fa-chart-line text-4xl mb-4 opacity-30"></i>
            <p>Belum ada data untuk ditampilkan</p>
          </div>
        </div>
        @endif

        <div id="chartLoader"
          class="absolute inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm rounded-lg">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Jenis Pekerjaan Section -->
  <section id="jenis-pekerjaan" class="py-20 px-6">
    <div class="max-w-5xl mx-auto mb-14 text-center">
      <h2 class="text-3xl font-bold text-white mb-4">Distribusi Jenis Pekerjaan</h2>
      <p class="text-slate-300 max-w-2xl mx-auto">
        @if(count($alumni) > 0 && isset($stats['job_type_distribution']) && count($stats['job_type_distribution']) > 0)
        Sebaran jenis pekerjaan alumni yang bekerja dan berwirausaha menunjukkan keberagaman karir dan bisnis yang dijalani lulusan Institut Prima Bangsa.
        @else
        Belum ada data distribusi jenis pekerjaan yang tersedia saat ini.
        @endif
      </p>
    </div>

    <div
      class="max-w-5xl mx-auto bg-gradient-to-br from-slate-800/60 to-slate-900/60 backdrop-blur-xl rounded-2xl p-8 shadow-xl border border-slate-700/30">
      <div class="grid md:grid-cols-4 gap-6">
        <div class="md:col-span-3">
          <div class="relative h-[450px]">
            @if(count($alumni) > 0 && isset($stats['job_type_distribution']) && count($stats['job_type_distribution']) > 0)
            <canvas id="jobTypeChart" class="max-w-full h-full mx-auto"></canvas>
            @else
            <div class="flex items-center justify-center h-full">
              <div class="text-slate-400 text-center">
                <i class="fas fa-chart-bar text-4xl mb-4 opacity-30"></i>
                <p>Belum ada data untuk ditampilkan</p>
              </div>
            </div>
            @endif

            <div id="jobChartLoader"
              class="absolute inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm rounded-lg">
              <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
            </div>
          </div>
        </div>

        <div class="md:col-span-1">
          <h3 class="text-xl font-bold text-white mb-4">Keterangan</h3>
          <div class="bg-slate-800/50 rounded-lg p-4 mb-4">
            <p class="text-slate-200 text-sm">
              Setiap warna menunjukkan jenis pekerjaan yang berbeda. Hover pada batang untuk melihat detail.
            </p>
          </div>

          <div class="bg-slate-800/50 rounded-lg p-4 mt-4">
            <h4 class="text-white font-medium mb-2">Tentang Visualisasi</h4>
            <p class="text-slate-300 text-sm">
              Diagram menampilkan distribusi jenis pekerjaan alumni berdasarkan data terbaru. Panjang bar menunjukkan jumlah alumni di setiap jenis pekerjaan.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Per Prodi Section -->
  <section id="per-prodi" class="py-20 px-6 bg-slate-900/50">
    <div class="max-w-5xl mx-auto mb-14 text-center">
      <h2 class="text-3xl font-bold text-white mb-4">Alumni Per Program Studi</h2>
      <p class="text-slate-300 max-w-2xl mx-auto">
        Distribusi jenis pekerjaan alumni berdasarkan program studi dan tahun kelulusan.
      </p>
    </div>

    <div
      class="max-w-5xl mx-auto bg-gradient-to-br from-slate-800/60 to-slate-900/60 backdrop-blur-xl rounded-2xl p-8 shadow-xl border border-slate-700/30">
      <div class="flex flex-wrap justify-center gap-6 mb-6">
        <div class="w-full md:w-auto">
          <label for="prodi-select" class="block text-white text-sm font-medium mb-2">Program Studi</label>
          <select id="prodi-select" class="w-full md:w-64 bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
            @foreach($prodis as $prodi)
              <option value="{{ $prodi->id }}">{{ $prodi->prodi_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="w-full md:w-auto">
          <label for="year-select" class="block text-white text-sm font-medium mb-2">Tahun Kelulusan</label>
          <select id="year-select" class="w-full md:w-40 bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
            <!-- Years will be populated by JavaScript based on prodi selection -->
          </select>
        </div>
      </div>

      <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-1 backdrop-blur-xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl p-6 shadow-xl border border-slate-700/30">
          <h3 class="text-xl font-bold text-white mb-4 text-center">Distribusi Pekerjaan</h3>
          <div class="relative">
            <canvas id="prodiJobChart" class="max-w-full mx-auto" height="280"></canvas>

            <div id="noProdiDataMessage" class="absolute inset-0 flex items-center justify-center hidden">
              <div class="text-slate-400 text-center">
                <i class="fas fa-chart-pie text-3xl mb-3 opacity-30"></i>
                <p>Belum ada data</p>
              </div>
            </div>

            <div id="prodiChartLoader" class="absolute inset-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm rounded-lg">
              <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
            </div>
          </div>
        </div>

        <div class="md:col-span-2">
          <h3 class="text-xl font-bold text-white mb-4">Detail Alumni <span id="prodi-year-title"></span></h3>
          <div id="prodi-details" class="bg-slate-800/50 rounded-lg p-4">
            <!-- Will be populated by JavaScript -->
            <p class="text-slate-400 text-center py-4">Pilih program studi dan tahun untuk melihat detail.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-10 bg-slate-900 border-t border-slate-800">
    <div class="max-w-6xl mx-auto px-6">
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="mb-6 md:mb-0">
          <a href="#" class="flex items-center">
            <img src="{{ asset('assets/logo.ico') }}" alt="Institut Prima Bangsa Logo" class="h-8 w-auto mr-2">
            <span class="text-white font-bold text-lg">Institut Prima Bangsa</span>
          </a>
          <p class="text-slate-400 mt-2 max-w-md">Melacak perjalanan karir alumni untuk membentuk masa depan pendidikan yang lebih baik.</p>
        </div>
        <div class="flex flex-col md:flex-row gap-8">
          <div>
            <h3 class="text-white font-semibold mb-3">Navigasi</h3>
            <ul class="space-y-2">
              <li><a href="#beranda" class="text-slate-400 hover:text-white">Beranda</a></li>
              <li><a href="#statistik" class="text-slate-400 hover:text-white">Statistik</a></li>
              <li><a href="#tren" class="text-slate-400 hover:text-white">Tren Alumni</a></li>
              <li><a href="#jenis-pekerjaan" class="text-slate-400 hover:text-white">Jenis Pekerjaan</a></li>
              <li><a href="#per-prodi" class="text-slate-400 hover:text-white">Per Prodi</a></li>
            </ul>
          </div>
          <div>
            <h3 class="text-white font-semibold mb-3">Kontak</h3>
            <ul class="space-y-2">
              <li class="flex items-center text-slate-400">
                <i class="fas fa-map-marker-alt w-5 text-primary-500"></i>
                <span>Jl. Brigjend Dharsono Bypass No.20,Kabupaten Cirebon</span>
              </li>
              <li class="flex items-center text-slate-400">
                <i class="fas fa-envelope w-5 text-primary-500"></i>
                <span>info@ipbcirebon.ac.id</span>
              </li>
              <li class="flex items-center text-slate-400">
                <i class="fas fa-phone w-5 text-primary-500"></i>
                <span>+62-838-2440-8999</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="border-t border-slate-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
        <p class="text-slate-500 text-sm mb-4 md:mb-0">© {{ date('Y') }} Institut Prima Bangsa. Semua hak dilindungi.</p>
        <div class="flex space-x-4">
          <a href="#" class="text-slate-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="text-slate-400 hover:text-white"><i class="fab fa-twitter"></i></a>
          <a href="#" class="text-slate-400 hover:text-white"><i class="fab fa-instagram"></i></a>
          <a href="#" class="text-slate-400 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
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
      // Hide chart loaders after charts are initialized
      setTimeout(() => {
        const chartLoader = document.getElementById('chartLoader');
        const jobChartLoader = document.getElementById('jobChartLoader');

        if (chartLoader) {
          chartLoader.style.display = 'none';
        }

        if (jobChartLoader) {
          jobChartLoader.style.display = 'none';
        }
      }, 1000);

      @if(count($alumni) > 0 && ($stats['bekerja_count'] + $stats['studi_lanjut_count'] + $stats['wirausaha_count'] + $stats['mengurus_keluarga_count'] > 0))
      // Status Pie Chart
      const statusPieChartCtx = document.getElementById('statusPieChart');
      if (statusPieChartCtx) {
        // Get percentages
        const bekerjaPercent = {{ $stats['bekerja_percent'] }};
        const studiLanjutPercent = {{ $stats['studi_lanjut_percent'] }};
        const wirausahaPercent = {{ $stats['wirausaha_percent'] }};
        const mengurusKeluargaPercent = {{ $stats['mengurus_keluarga_percent'] }};

        const statusPieChart = new Chart(statusPieChartCtx, {
          type: 'doughnut',
          data: {
            labels: ['Bekerja', 'Lanjut Studi', 'Wirausaha', 'Mengurus Keluarga'],
            datasets: [{
              data: [bekerjaPercent, studiLanjutPercent, wirausahaPercent, mengurusKeluargaPercent],
              backgroundColor: [
                'rgba(34, 197, 94, 0.8)',  // Green for working
                'rgba(59, 130, 246, 0.8)',  // Blue for studying
                'rgba(249, 115, 22, 0.8)',  // Orange for entrepreneurship
                'rgba(139, 92, 246, 0.8)'   // Purple for family management
              ],
              borderColor: [
                'rgba(34, 197, 94, 1)',
                'rgba(59, 130, 246, 1)',
                'rgba(249, 115, 22, 1)',
                'rgba(139, 92, 246, 1)'
              ],
              borderWidth: 1
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
                  padding: 15,
                  font: {
                    size: 11
                  }
                }
              },
              tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.8)',
                titleColor: '#fff',
                bodyColor: '#cbd5e1',
                padding: 10,
                borderColor: 'rgba(148, 163, 184, 0.2)',
                borderWidth: 1,
                displayColors: true,
                usePointStyle: true,
                callbacks: {
                  label: function(context) {
                    const label = context.label || '';
                    const value = context.raw || 0;
                    return `${label}: ${value.toFixed(1)}%`;
                  }
                }
              }
            },
            cutout: '60%',
            animation: {
              animateScale: true,
              animateRotate: true
            }
          }
        });
      }
      @endif

      @if(count($alumni) > 0)
      // Alumni Chart
      const alumniChartCtx = document.getElementById('alumniChart');
      if (alumniChartCtx) {
        // Mendapatkan data dari controller
        const yearlyData = @json($stats['yearly_data'] ?? []);

        // Ekstrak tahun dan urutkan
        const years = Object.keys(yearlyData).sort();

        // Siapkan data untuk chart
        const bekerjaData = years.map(year => yearlyData[year]?.bekerja_percent || 0);
        const studiData = years.map(year => yearlyData[year]?.studi_lanjut_percent || 0);
        const wirausahaData = years.map(year => yearlyData[year]?.wirausaha_percent || 0);
        const mengurusKeluargaData = years.map(year => yearlyData[year]?.mengurus_keluarga_percent || 0);

        const alumniChart = new Chart(alumniChartCtx, {
          type: 'line',
          data: {
            labels: years,
            datasets: [
              {
                label: 'Bekerja',
                data: bekerjaData,
                borderColor: '#22C55E',
                backgroundColor: 'rgba(34,197,94,0.1)',
                tension: 0.4,
                fill: true
              },
              {
                label: 'Lanjut Studi',
                data: studiData,
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                tension: 0.4,
                fill: true
              },
              {
                label: 'Wirausaha',
                data: wirausahaData,
                borderColor: '#F97316',
                backgroundColor: 'rgba(249,115,22,0.1)',
                tension: 0.4,
                fill: true
              },
              {
                label: 'Mengurus Keluarga',
                data: mengurusKeluargaData,
                borderColor: '#8B5CF6',
                backgroundColor: 'rgba(139,92,246,0.1)',
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
                  data: bekerjaData,
                  borderColor: '#22C55E',
                  backgroundColor: 'rgba(34,197,94,0.1)',
                  tension: 0.4,
                  fill: true
                },
                {
                  label: 'Lanjut Studi',
                  data: studiData,
                  borderColor: '#3B82F6',
                  backgroundColor: 'rgba(59,130,246,0.1)',
                  tension: 0.4,
                  fill: true
                },
                {
                  label: 'Wirausaha',
                  data: wirausahaData,
                  borderColor: '#F97316',
                  backgroundColor: 'rgba(249,115,22,0.1)',
                  tension: 0.4,
                  fill: true
                },
                {
                  label: 'Mengurus Keluarga',
                  data: mengurusKeluargaData,
                  borderColor: '#8B5CF6',
                  backgroundColor: 'rgba(139,92,246,0.1)',
                  tension: 0.4,
                  fill: true
                }
              ];
            }
            alumniChart.update();
          });
        });
      }
      @endif

      @if(count($alumni) > 0 && isset($stats['job_type_distribution']) && count($stats['job_type_distribution']) > 0)
      // Job Type Chart
      const jobTypeChartCtx = document.getElementById('jobTypeChart');
      if (jobTypeChartCtx) {
        // Get job type data from controller
        const jobTypeData = @json($stats['job_type_distribution'] ?? []);

        // Get all job types
        const allJobTypes = Object.keys(jobTypeData);
        const jobTypeCounts = allJobTypes.map(type => jobTypeData[type].count);

        // Create an array of vibrant colors for each job type
        const colorPalette = [
          'rgba(34, 197, 94, 0.8)',   // green
          'rgba(249, 115, 22, 0.8)',  // orange
          'rgba(59, 130, 246, 0.8)',  // blue
          'rgba(139, 92, 246, 0.8)',  // purple
          'rgba(236, 72, 153, 0.8)',  // pink
          'rgba(234, 179, 8, 0.8)',   // yellow
          'rgba(16, 185, 129, 0.8)',  // emerald
          'rgba(6, 182, 212, 0.8)',   // cyan
          'rgba(99, 102, 241, 0.8)',  // indigo
          'rgba(244, 63, 94, 0.8)',   // rose
          'rgba(168, 85, 247, 0.8)',  // fuchsia
          'rgba(20, 184, 166, 0.8)',  // teal
          'rgba(245, 158, 11, 0.8)',  // amber
          'rgba(8, 145, 178, 0.8)',   // sky
          'rgba(217, 70, 239, 0.8)',  // violet
        ];

        // Assign colors to each job type
        const backgroundColors = allJobTypes.map((_, index) => {
          return colorPalette[index % colorPalette.length];
        });

        const borderColors = backgroundColors.map(color => {
          return color.replace('0.8', '1');
        });

        const jobTypeChart = new Chart(jobTypeChartCtx, {
          type: 'bar',  // Horizontal bar chart
          data: {
            labels: allJobTypes,
            datasets: [{
              label: 'Jumlah Alumni',
              data: jobTypeCounts,
              backgroundColor: backgroundColors,
              borderColor: borderColors,
              borderWidth: 1,
              borderRadius: 4,
            }]
          },
          options: {
            indexAxis: 'y',  // Horizontal bars
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              },
              tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.8)',
                titleColor: '#fff',
                bodyColor: '#cbd5e1',
                padding: 12,
                cornerRadius: 8,
                borderColor: 'rgba(148, 163, 184, 0.2)',
                borderWidth: 1,
                callbacks: {
                  label: function(context) {
                    const count = context.raw;
                    return `Jumlah: ${count} alumni`;
                  },
                  title: function(context) {
                    return context[0].label;
                  }
                }
              }
            },
            scales: {
              y: {
                ticks: {
                  color: '#fff',
                  font: {
                    size: 11
                  },
                  padding: 10
                },
                grid: {
                  display: false
                }
              },
              x: {
                beginAtZero: true,
                ticks: {
                  precision: 0,
                  color: '#94a3b8',
                  font: {
                    size: 11
                  }
                },
                grid: {
                  color: 'rgba(148, 163, 184, 0.1)',
                },
                title: {
                  display: true,
                  text: 'Jumlah Alumni',
                  color: '#cbd5e1',
                  font: {
                    size: 12
                  }
                }
              }
            },
            layout: {
              padding: {
                left: 10,
                right: 25,
                top: 0,
                bottom: 10
              }
            }
          }
        });
      }
      @endif

      // Prodi-specific chart and data handling
      // Hide prodi chart loader after initialization
      setTimeout(() => {
        const prodiChartLoader = document.getElementById('prodiChartLoader');
        if (prodiChartLoader) {
          prodiChartLoader.style.display = 'none';
        }
      }, 1000);

      // Get DOM elements
      const prodiSelect = document.getElementById('prodi-select');
      const yearSelect = document.getElementById('year-select');
      const prodiJobChartCtx = document.getElementById('prodiJobChart');
      const prodiYearTitle = document.getElementById('prodi-year-title');
      const prodiDetails = document.getElementById('prodi-details');
      const noProdiDataMessage = document.getElementById('noProdiDataMessage');

      // Initialize variables
      let prodiJobChart = null;
      const prodiStatsData = @json($prodiStats ?? []);

      // Log the data to see what's available
      console.log("Prodi Stats Data:", prodiStatsData);

      // Function to populate year dropdown based on selected prodi
      function populateYearDropdown(prodiId) {
        if (!yearSelect) return;

        yearSelect.innerHTML = '';

        if (prodiStatsData[prodiId] && prodiStatsData[prodiId].yearly_stats) {
          const years = Object.keys(prodiStatsData[prodiId].yearly_stats).sort((a, b) => b - a);

          if (years.length > 0) {
            years.forEach(year => {
              const option = document.createElement('option');
              option.value = year;
              option.textContent = year;
              yearSelect.appendChild(option);
            });

            // Trigger update for the first year
            if (yearSelect.value) {
              updateProdiJobChart(prodiId, yearSelect.value);
            } else if (years.length > 0) {
              yearSelect.value = years[0];
              updateProdiJobChart(prodiId, years[0]);
            }

            // Hide "no data" message
            if (noProdiDataMessage) {
              noProdiDataMessage.classList.add('hidden');
            }
          } else {
            displayNoData();
          }
        } else {
          displayNoData();
        }
      }

      // Function to display "No data" message
      function displayNoData() {
        if (prodiYearTitle) {
          prodiYearTitle.textContent = " (Tidak ada data)";
        }

        if (prodiDetails) {
          prodiDetails.innerHTML = '<p class="text-slate-400 text-center py-4">Tidak ada data yang tersedia.</p>';
        }

        // Show "no data" message
        if (noProdiDataMessage) {
          noProdiDataMessage.classList.remove('hidden');
        }

        // Clear chart if it exists
        if (prodiJobChart) {
          prodiJobChart.destroy();
          prodiJobChart = null;
        }
      }

      // Function to update the job distribution chart
      function updateProdiJobChart(prodiId, year) {
        console.log("Updating chart for prodi:", prodiId, "year:", year);

        // Get job distribution data
        const prodiName = prodiStatsData[prodiId]?.name || 'Unknown';
        const yearData = prodiStatsData[prodiId]?.yearly_stats[year] || null;

        console.log("Year data:", yearData);

        // Update title
        if (prodiYearTitle) {
          prodiYearTitle.textContent = ` ${prodiName} - ${year}`;
        }

        // If no data, display message and return
        if (!yearData || !yearData.job_distribution || Object.keys(yearData.job_distribution).length === 0) {
          if (prodiDetails) {
            prodiDetails.innerHTML = `<p class="text-slate-400 text-center py-4">Tidak ada data distribusi pekerjaan untuk ${prodiName} angkatan ${year}.</p>`;
          }

          // Show "no data" message
          if (noProdiDataMessage) {
            noProdiDataMessage.classList.remove('hidden');
          }

          // Clear chart if it exists
          if (prodiJobChart) {
            prodiJobChart.destroy();
            prodiJobChart = null;
          }
          return;
        }

        // Hide "no data" message
        if (noProdiDataMessage) {
          noProdiDataMessage.classList.add('hidden');
        }

        // Prepare chart data
        const jobLabels = Object.keys(yearData.job_distribution);
        const jobCounts = jobLabels.map(job => yearData.job_distribution[job]);

        console.log("Job labels:", jobLabels);
        console.log("Job counts:", jobCounts);

        // Generate colors for each job type
        const colorPalette = [
          'rgba(34, 197, 94, 0.8)',   // green
          'rgba(249, 115, 22, 0.8)',  // orange
          'rgba(59, 130, 246, 0.8)',  // blue
          'rgba(139, 92, 246, 0.8)',  // purple
          'rgba(236, 72, 153, 0.8)',  // pink
          'rgba(234, 179, 8, 0.8)',   // yellow
          'rgba(16, 185, 129, 0.8)',  // emerald
          'rgba(6, 182, 212, 0.8)',   // cyan
          'rgba(99, 102, 241, 0.8)',  // indigo
          'rgba(244, 63, 94, 0.8)',   // rose
        ];

        const backgroundColors = jobLabels.map((_, index) => {
          return colorPalette[index % colorPalette.length];
        });

        const borderColors = backgroundColors.map(color => {
          return color.replace('0.8', '1');
        });

        // Create or update chart
        if (prodiJobChart) {
          console.log("Updating existing chart");
          prodiJobChart.data.labels = jobLabels;
          prodiJobChart.data.datasets[0].data = jobCounts;
          prodiJobChart.data.datasets[0].backgroundColor = backgroundColors;
          prodiJobChart.data.datasets[0].borderColor = borderColors;
          prodiJobChart.update();
        } else if (prodiJobChartCtx) {
          console.log("Creating new chart");
          prodiJobChart = new Chart(prodiJobChartCtx, {
            type: 'pie',
            data: {
              labels: jobLabels,
              datasets: [{
                data: jobCounts,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 1
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
                    padding: 15,
                    font: { size: 11 }
                  }
                },
                tooltip: {
                  backgroundColor: 'rgba(15, 23, 42, 0.8)',
                  titleColor: '#fff',
                  bodyColor: '#cbd5e1',
                  padding: 10,
                  borderColor: 'rgba(148, 163, 184, 0.2)',
                  borderWidth: 1,
                  displayColors: true,
                  usePointStyle: true,
                  callbacks: {
                    label: function(context) {
                      const label = context.label || '';
                      const value = context.raw || 0;
                      const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                      const percentage = Math.round((value / total) * 100);
                      return `${label}: ${value} alumni (${percentage}%)`;
                    }
                  }
                }
              },
              animation: {
                animateScale: true,
                animateRotate: true
              }
            }
          });
        }

        // Update details section
        let detailsHTML = '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">';
        let total = 0;

        jobLabels.forEach((job, index) => {
          const count = yearData.job_distribution[job];
          total += count;
          const color = backgroundColors[index].replace('0.8', '1');

          detailsHTML += `
            <div class="bg-slate-700/50 rounded-lg p-3">
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full" style="background-color: ${color}"></div>
                <h4 class="text-white font-medium">${job}</h4>
              </div>
              <p class="text-slate-300 ml-5">${count} alumni</p>
            </div>
          `;
        });

        detailsHTML += '</div>';

        if (total > 0) {
          detailsHTML += `<div class="mt-4 text-center text-slate-300">Total: ${total} alumni dengan informasi pekerjaan</div>`;
        }

        if (prodiDetails) {
          prodiDetails.innerHTML = detailsHTML;
        }
      }

      // Event listeners
      if (prodiSelect) {
        prodiSelect.addEventListener('change', function() {
          console.log("Prodi changed to:", this.value);
          populateYearDropdown(this.value);
        });
      }

      if (yearSelect) {
        yearSelect.addEventListener('change', function() {
          console.log("Year changed to:", this.value);
          const prodiId = prodiSelect.value;
          updateProdiJobChart(prodiId, this.value);
        });
      }

      // Initialize with first prodi
      if (prodiSelect && Object.keys(prodiStatsData).length > 0) {
        const firstProdiId = Object.keys(prodiStatsData)[0];
        console.log("Initializing with first prodi:", firstProdiId);
        prodiSelect.value = firstProdiId;
        populateYearDropdown(firstProdiId);
      } else {
        console.log("No prodi data available");
        displayNoData();
      }
    });
  </script>
</body>
</html>
