<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Terima Kasih - Institut Prima Bangsa</title>

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

        .thank-you-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        /* Animation */
        @keyframes successAnimation {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-animation {
            animation: successAnimation 1s ease-out forwards;
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
            @if(session('success'))
                <div class="bg-green-900 bg-opacity-90 text-green-400 p-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Thank You Card -->
            <div class="thank-you-card rounded-xl p-8 shadow-lg text-center">
                <div class="py-8">
                    <div class="w-24 h-24 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center mx-auto mb-6 success-animation">
                        <i class="fas fa-check-circle text-5xl"></i>
                    </div>
                    <h2 class="text-2xl font-semibold text-white mb-4">Terima Kasih!</h2>
                    <p class="text-gray-300 text-lg max-w-xl mx-auto mb-8">
                        Jawaban Anda telah berhasil disimpan. Kontribusi Anda sangat berharga untuk pengembangan Institut Prima Bangsa.
                    </p>

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

            <!-- Share Card -->
            <div class="thank-you-card rounded-xl p-6 shadow-lg mt-6">
                <h3 class="text-lg font-semibold text-white mb-4 text-center">Bagikan Form</h3>
                <p class="text-gray-300 text-center mb-4">
                    Bantu kami menjangkau lebih banyak alumni dengan membagikan form ini.
                </p>

                <div class="flex flex-wrap justify-center gap-3">
                    <a href="https://wa.me/?text={{ urlencode('Kuesioner Alumni Institut Prima Bangsa: ' . $form->getFormUrl()) }}"
                        target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </a>
                    <a href="https://telegram.me/share/url?url={{ urlencode($form->getFormUrl()) }}&text={{ urlencode('Kuesioner Alumni Institut Prima Bangsa') }}"
                        target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fab fa-telegram mr-2"></i> Telegram
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($form->getFormUrl()) }}"
                        target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fab fa-facebook mr-2"></i> Facebook
                    </a>
                    <button id="copyLinkBtn" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg flex items-center"
                        data-clipboard-text="{{ $form->getFormUrl() }}">
                        <i class="fas fa-link mr-2"></i> Salin Link
                    </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Copy link button
            const copyLinkBtn = document.getElementById('copyLinkBtn');

            if (copyLinkBtn) {
                copyLinkBtn.addEventListener('click', function() {
                    const link = this.getAttribute('data-clipboard-text');
                    navigator.clipboard.writeText(link).then(function() {
                        const originalText = copyLinkBtn.innerHTML;
                        copyLinkBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Link Disalin!';
                        copyLinkBtn.classList.add('bg-green-600');
                        copyLinkBtn.classList.remove('bg-slate-600', 'hover:bg-slate-700');

                        setTimeout(function() {
                            copyLinkBtn.innerHTML = originalText;
                            copyLinkBtn.classList.remove('bg-green-600');
                            copyLinkBtn.classList.add('bg-slate-600', 'hover:bg-slate-700');
                        }, 2000);
                    }).catch(function() {
                        alert('Gagal menyalin link. Silakan coba lagi.');
                    });
                });
            }
        });
    </script>
</body>
</html>
