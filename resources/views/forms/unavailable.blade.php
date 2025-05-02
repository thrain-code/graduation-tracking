<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Form Tidak Tersedia - Institut Prima Bangsa</title>

    <!-- Font -->
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

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #f8fafc;
            min-height: 100vh;
        }

        .error-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        /* Animation */
        @keyframes pulseAnimation {
            0% {
                transform: scale(1);
                opacity: 0.7;
            }
            50% {
                transform: scale(1.05);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 0.7;
            }
        }

        .pulse-animation {
            animation: pulseAnimation 2s infinite;
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
<body class="flex flex-col min-h-screen">
    <!-- Header -->
    <header class="bg-slate-900 bg-opacity-90 backdrop-blur-lg p-4 shadow-md sticky top-0 z-10">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('assets/logo.ico') }}" alt="Institut Prima Bangsa Logo" class="h-8 w-auto mr-3">
                <span class="text-white font-bold text-xl">Institut Prima Bangsa</span>
            </div>

            <div>
                <a href="{{ route('form.results', $form->slug) }}" class="text-primary-400 hover:text-primary-300">
                    <i class="fas fa-chart-bar mr-1"></i> Lihat Hasil
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow p-4 flex items-center justify-center">
        <div class="max-w-2xl w-full">
            <!-- Error Card -->
            <div class="error-card rounded-xl p-8 shadow-lg text-center">
                <div class="py-8">
                    <div class="w-24 h-24 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center mx-auto mb-6 pulse-animation">
                        <i class="fas fa-exclamation-circle text-5xl"></i>
                    </div>
                    <h2 class="text-2xl font-semibold text-white mb-4">Form Tidak Tersedia</h2>

                    @if($form->isExpired())
                        <p class="text-gray-300 text-lg max-w-xl mx-auto mb-4">
                            Maaf, form ini telah berakhir dan tidak lagi menerima tanggapan.
                        </p>
                        <p class="text-gray-400 mb-8">
                            Form berakhir pada: {{ $form->expires_at->format('d M Y H:i') }}
                        </p>
                    @elseif(!$form->is_active)
                        <p class="text-gray-300 text-lg max-w-xl mx-auto mb-8">
                            Maaf, form ini sedang tidak aktif. Silakan hubungi administrator untuk informasi lebih lanjut.
                        </p>
                    @else
                        <p class="text-gray-300 text-lg max-w-xl mx-auto mb-8">
                            Maaf, form ini tidak tersedia untuk diisi saat ini. Silakan coba lagi nanti.
                        </p>
                    @endif

                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('form.results', $form->slug) }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-bar mr-2"></i> Lihat Hasil
                        </a>
                        <a href="{{ url('/') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-6 py-3 rounded-lg flex items-center justify-center">
                            <i class="fas fa-home mr-2"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Card -->
            <div class="error-card rounded-xl p-6 shadow-lg mt-6">
                <h3 class="text-lg font-semibold text-white mb-4 text-center">Butuh Bantuan?</h3>
                <p class="text-gray-300 text-center mb-4">
                    Jika Anda memiliki pertanyaan atau kesulitan mengakses form, silakan hubungi administrator.
                </p>

                <div class="flex flex-col items-center gap-2">
                    <a href="mailto:info@ipbcirebon.ac.id" class="text-primary-400 hover:text-primary-300 flex items-center">
                        <i class="fas fa-envelope mr-2"></i> info@ipbcirebon.ac.id
                    </a>
                    <span class="text-gray-300 flex items-center">
                        <i class="fas fa-phone mr-2"></i> +62-838-2440-8999
                    </span>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 py-6 mt-auto">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <div class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Institut Prima Bangsa - Sistem Pelacakan Alumni
            </div>
        </div>
    </footer>
</body>
</html>
