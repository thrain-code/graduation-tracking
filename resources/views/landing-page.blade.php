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

      <div class="grid md:grid-cols-2 gap-6">
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
            </ul>
          </div>
          <div>
            <h3 class="text-white font-semibold mb-3">Kontak</h3>
            <ul class="space-y-2">
              <li class="flex items-center text-slate-400">
                <i class="fas fa-map-marker-alt w-5 text-primary-500"></i>
                <span>Jl. Pendidikan No. 123, Jakarta</span>
              </li>
              <li class="flex items-center text-slate-400">
                <i class="fas fa-envelope w-5 text-primary-500"></i>
                <span>info@institutprimabangsa.ac.id</span>
              </li>
              <li class="flex items-center text-slate-400">
                <i class="fas fa-phone w-5 text-primary-500"></i>
                <span>+62 21 5569 9876</span>
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
      // Hide chart loader after charts are initialized
      setTimeout(() => {
        const chartLoader = document.getElementById('chartLoader');
        if (chartLoader) {
          chartLoader.style.display = 'none';
        }
      }, 1000);

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
        const mencariKerjaData = years.map(year => yearlyData[year]?.mencari_kerja_percent || 0);

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
                }
              ];
            } 
            alumniChart.update();
          });
        });
      }
      @endif
    });
  </script>
</body>
</html>