<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hasil Form: {{ $form->title }} - Institut Prima Bangsa</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

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

        .result-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        .result-card:hover {
            transform: translateY(-5px);
        }

        .progress-bar {
            transition: width 1s ease-in-out;
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

        .text-response {
            border-left: 3px solid rgba(14, 165, 233, 0.3);
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <!-- Header -->
    <header class="bg-slate-900 bg-opacity-90 backdrop-blur-lg p-4 shadow-md sticky top-0 z-10">
        <div class="max-w-5xl mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('assets/logo.ico') }}" alt="Institut Prima Bangsa Logo" class="h-8 w-auto mr-3">
                <span class="text-white font-bold text-xl">Institut Prima Bangsa</span>
            </div>

            <div>
                <a href="{{ route('form.show', $form->slug) }}" class="text-primary-400 hover:text-primary-300">
                    <i class="fas fa-clipboard-list mr-1"></i> Kembali ke Form
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow p-4">
        <div class="max-w-5xl mx-auto">
            <!-- Form Header -->
            <div class="result-card rounded-xl p-6 shadow-lg mb-6">
                <h1 class="text-2xl font-bold text-white mb-2">Hasil Form: {{ $form->title }}</h1>

                @if($form->description)
                    <p class="text-gray-300 mb-4">{{ $form->description }}</p>
                @endif

                <div class="flex flex-wrap gap-4 mt-4">
                    <div class="bg-slate-800 rounded-lg px-4 py-3">
                        <p class="text-gray-400 text-sm">Total Responden</p>
                        <p class="text-2xl font-bold text-white">{{ $totalResponses }}</p>
                    </div>

                    <div class="bg-slate-800 rounded-lg px-4 py-3">
                        <p class="text-gray-400 text-sm">Total Pertanyaan</p>
                        <p class="text-2xl font-bold text-white">{{ count($resultsData) }}</p>
                    </div>

                    <div class="bg-slate-800 rounded-lg px-4 py-3">
                        <p class="text-gray-400 text-sm">Terakhir Diperbarui</p>
                        <p class="text-xl font-bold text-white">{{ now()->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($totalResponses > 0)
                <div class="mb-6 p-4 bg-primary-900/30 border border-primary-800/50 rounded-lg">
                    <div class="flex items-start">
                        <div class="bg-primary-500/20 text-primary-400 p-2 rounded-lg mr-4">
                            <i class="fas fa-info-circle text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-primary-300 font-medium mb-1">Tentang Hasil</h3>
                            <p class="text-gray-300">
                                Hasil yang ditampilkan berikut ini menggambarkan data yang telah dikumpulkan dari para responden.
                                Harap diingat bahwa hasil ini mungkin tidak mewakili seluruh populasi dan hanya mencerminkan tanggapan
                                dari responden yang telah mengisi form.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Results -->
                @foreach($resultsData as $questionData)
                    <div class="result-card rounded-xl p-6 shadow-lg mb-6" id="question-{{ $questionData['id'] }}">
                        <h3 class="text-lg font-semibold text-white mb-4">{{ $questionData['text'] }}</h3>

                        <div class="mb-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400">Tipe: {{ $questionData['type'] == 'multiple_choice' ? 'Pilihan Ganda' : 'Teks' }}</span>
                                <span class="text-gray-400">Responden: {{ $questionData['total_responses'] }}</span>
                            </div>
                        </div>

                        @if($questionData['type'] == 'multiple_choice')
                            <!-- Bar Graph Container -->
                            <div class="mb-6">
                                <canvas class="result-chart" id="chart-{{ $questionData['id'] }}" height="300"></canvas>
                            </div>

                            <!-- Results Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-slate-800 text-gray-300 text-sm">
                                        <tr>
                                            <th class="px-3 py-2 rounded-tl-lg">Pilihan</th>
                                            <th class="px-3 py-2 text-center">Jumlah Responden</th>
                                            <th class="px-3 py-2 text-center">Persentase</th>
                                            <th class="px-3 py-2 rounded-tr-lg">Distribusi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-300">
                                        @foreach($questionData['options'] as $option)
                                            <tr class="border-b border-slate-700">
                                                <td class="px-3 py-2">{{ $option['text'] }}</td>
                                                <td class="px-3 py-2 text-center">{{ $option['count'] }}</td>
                                                <td class="px-3 py-2 text-center">{{ $option['percentage'] }}%</td>
                                                <td class="px-3 py-2">
                                                    <div class="w-full bg-slate-700 rounded-full h-2.5 overflow-hidden">
                                                        <div class="bg-primary-500 h-2.5 rounded-full progress-bar" style="width: {{ $option['percentage'] }}%"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <!-- Text Responses Summary -->
                            <div class="bg-slate-800 rounded-lg p-4">
                                <h4 class="text-white font-medium mb-4">Ringkasan Jawaban Teks ({{ count($questionData['text_responses']) }})</h4>

                                @if(count($questionData['text_responses']) > 0)
                                    <div class="text-gray-300">
                                        <p>
                                            Terdapat {{ count($questionData['text_responses']) }} jawaban teks untuk pertanyaan ini.
                                            Jawaban teks tidak ditampilkan secara detail untuk menjaga privasi responden.
                                        </p>

                                        @php
                                            // Get some simple stats about text length
                                            $lengths = array_map('strlen', $questionData['text_responses']);
                                            $avgLength = count($lengths) > 0 ? array_sum($lengths) / count($lengths) : 0;
                                            $maxLength = count($lengths) > 0 ? max($lengths) : 0;
                                            $minLength = count($lengths) > 0 ? min($lengths) : 0;
                                        @endphp

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                                            <div class="bg-slate-700/50 rounded-lg p-3">
                                                <p class="text-gray-400 text-sm">Rata-rata Panjang Jawaban</p>
                                                <p class="text-lg font-medium text-white">{{ round($avgLength) }} karakter</p>
                                            </div>
                                            <div class="bg-slate-700/50 rounded-lg p-3">
                                                <p class="text-gray-400 text-sm">Jawaban Terpanjang</p>
                                                <p class="text-lg font-medium text-white">{{ $maxLength }} karakter</p>
                                            </div>
                                            <div class="bg-slate-700/50 rounded-lg p-3">
                                                <p class="text-gray-400 text-sm">Jawaban Terpendek</p>
                                                <p class="text-lg font-medium text-white">{{ $minLength }} karakter</p>
                                            </div>
                                        </div>

                                        <!-- Sample Responses (first 3 only) -->
                                        @if(count($questionData['text_responses']) > 0)
                                            <div class="mt-4">
                                                <h5 class="text-white font-medium mb-2">Contoh Jawaban:</h5>
                                                <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
                                                    @foreach(array_slice($questionData['text_responses'], 0, 3) as $response)
                                                        <div class="text-response bg-slate-700/50 rounded-lg p-3 pl-4">
                                                            <p class="text-gray-300">{{ $response }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-center py-6 text-gray-400">
                                        <i class="fas fa-comment-slash text-3xl mb-2"></i>
                                        <p>Belum ada responden yang menjawab pertanyaan ini.</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="result-card rounded-xl p-6 shadow-lg text-center">
                    <div class="py-10">
                        <i class="fas fa-chart-bar text-5xl text-gray-600 mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">Belum Ada Responden</h3>
                        <p class="text-gray-400 max-w-xl mx-auto">
                            Form ini belum memiliki responden. Hasil akan ditampilkan setelah beberapa orang mengisi form.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('form.show', $form->slug) }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg inline-block">
                                <i class="fas fa-clipboard-list mr-2"></i> Isi Form
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 py-6 mt-auto">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <div class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Institut Prima Bangsa - Sistem Pelacakan Alumni
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart configuration
            Chart.register(ChartDataLabels);

            // Generate charts for multiple choice questions
            const resultsData = @json($resultsData);

            resultsData.forEach(question => {
                if (question.type === 'multiple_choice') {
                    const ctx = document.getElementById(`chart-${question.id}`).getContext('2d');

                    // Prepare data
                    const labels = question.options.map(option => option.text);
                    const data = question.options.map(option => option.count);
                    const percentages = question.options.map(option => option.percentage);

                    // Generate colors
                    const generateColors = (count) => {
                        const baseColor = [14, 165, 233]; // RGB for primary-500
                        const colors = [];

                        for (let i = 0; i < count; i++) {
                            const opacity = 0.7 + (i * 0.3 / count);
                            colors.push(`rgba(${baseColor[0]}, ${baseColor[1]}, ${baseColor[2]}, ${opacity})`);
                        }

                        return colors;
                    };

                    const backgroundColor = generateColors(labels.length);
                    const borderColor = backgroundColor.map(color => color.replace(/[\d.]+\)$/, '1)'));

                    // Create chart
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Responden',
                                data: data,
                                backgroundColor: backgroundColor,
                                borderColor: borderColor,
                                borderWidth: 1,
                                borderRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                datalabels: {
                                    color: '#fff',
                                    formatter: (value, context) => {
                                        return value + ' (' + percentages[context.dataIndex] + '%)';
                                    },
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    },
                                    anchor: 'end',
                                    align: 'end',
                                    offset: 5
                                },
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    titleColor: '#fff',
                                    bodyColor: '#e2e8f0',
                                    displayColors: false,
                                    callbacks: {
                                        title: (tooltipItems) => {
                                            return tooltipItems[0].label;
                                        },
                                        label: (context) => {
                                            return [
                                                'Jumlah: ' + context.raw + ' responden',
                                                'Persentase: ' + percentages[context.dataIndex] + '%'
                                            ];
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(148, 163, 184, 0.1)'
                                    },
                                    border: {
                                        dash: [4, 4]
                                    },
                                    ticks: {
                                        color: '#94a3b8',
                                        precision: 0
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#94a3b8'
                                    }
                                }
                            }
                        }
                    });
                }
            });

            // Animate progress bars
            const progressBars = document.querySelectorAll('.progress-bar');

            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';

                setTimeout(() => {
                    bar.style.width = width;
                }, 300);
            });
        });
    </script>
</body>
</html>
