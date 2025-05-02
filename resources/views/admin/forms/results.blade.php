@extends('layouts.admin')

@section('title', 'Hasil Form')

@section('page-title', 'Hasil Form: ' . $form->title)

@push('styles')
<style>
    .result-card {
        transition: all 0.3s ease;
    }

    .result-card:hover {
        transform: translateY(-5px);
    }

    .progress-bar {
        transition: width 1s ease-in-out;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    .text-response {
        border-left: 3px solid rgba(14, 165, 233, 0.3);
    }
</style>
@endpush

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('forms.index') }}" class="text-primary-400 hover:text-primary-300 flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Form
    </a>

    <div class="flex items-center space-x-3">
        <a href="{{ route('forms.show', $form->id) }}" class="text-white bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-eye mr-2"></i> Lihat Form
        </a>
        <a href="{{ route('form.results', $form->slug) }}" target="_blank" class="text-white bg-primary-600 hover:bg-primary-700 px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-external-link-alt mr-2"></i> Lihat Publik
        </a>
    </div>
</div>

<!-- Summary Card -->
<div class="card rounded-xl p-6 shadow-lg mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h3 class="text-xl font-semibold text-white">Ringkasan Hasil</h3>
            <p class="text-gray-400">Menampilkan data dari {{ $totalResponses }} responden</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="#" id="printResults" class="text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-print mr-2"></i> Cetak
            </a>
            <a href="#" id="downloadResults" class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-download mr-2"></i> Download
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-slate-800 rounded-lg p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-sm">Total Responden</p>
                    <h4 class="text-2xl font-bold text-white">{{ $totalResponses }}</h4>
                </div>
                <div class="bg-blue-500/20 text-blue-400 p-2 rounded-lg">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-lg p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-sm">Total Pertanyaan</p>
                    <h4 class="text-2xl font-bold text-white">{{ count($resultsData) }}</h4>
                </div>
                <div class="bg-green-500/20 text-green-400 p-2 rounded-lg">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-lg p-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-sm">Dibuat Pada</p>
                    <h4 class="text-lg font-bold text-white">{{ $form->created_at->format('d M Y') }}</h4>
                </div>
                <div class="bg-purple-500/20 text-purple-400 p-2 rounded-lg">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Results -->
<div id="resultsContainer">
    @if($totalResponses > 0)
        @foreach($resultsData as $questionData)
            <div class="card result-card rounded-xl p-6 shadow-lg mb-6" id="question-{{ $questionData['id'] }}">
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
                    <!-- Text Responses -->
                    <div class="bg-slate-800 rounded-lg p-4">
                        <h4 class="text-white font-medium mb-4">Jawaban Teks ({{ count($questionData['text_responses']) }})</h4>

                        @if(count($questionData['text_responses']) > 0)
                            <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                                @foreach($questionData['text_responses'] as $response)
                                    <div class="text-response bg-slate-700/50 rounded-lg p-3 pl-4">
                                        <p class="text-gray-300">{{ $response }}</p>
                                    </div>
                                @endforeach
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
        <div class="card rounded-xl p-6 shadow-lg text-center">
            <div class="py-10">
                <i class="fas fa-chart-bar text-5xl text-gray-600 mb-4"></i>
                <h3 class="text-xl font-semibold text-white mb-2">Belum Ada Responden</h3>
                <p class="text-gray-400 max-w-xl mx-auto">
                    Form ini belum memiliki responden. Bagikan link form kepada alumni untuk mulai mengumpulkan data.
                </p>
                <div class="mt-6">
                    <div class="bg-slate-800 rounded-lg p-4 inline-block">
                        <p class="text-gray-400 mb-2 text-sm">Link Form:</p>
                        <div class="flex items-center">
                            <input type="text" value="{{ $form->getFormUrl() }}" readonly
                                class="bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                            <button id="copyLinkBtn" class="ml-2 bg-primary-600 hover:bg-primary-700 text-white px-3 py-2 rounded-lg"
                                data-clipboard-text="{{ $form->getFormUrl() }}">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

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

                // Create chart
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Responden',
                            data: data,
                            backgroundColor: 'rgba(14, 165, 233, 0.7)',
                            borderColor: 'rgba(14, 165, 233, 1)',
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

        // Print results
        document.getElementById('printResults').addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });

        // Download results as PDF
        document.getElementById('downloadResults').addEventListener('click', function(e) {
            e.preventDefault();

            // Alert user that PDF is being generated
            window.showAlert('Memproses PDF... Harap tunggu.', 'info');

            const element = document.getElementById('resultsContainer');
            const title = document.title;

            // HTML2PDF options
            const opt = {
                margin: [10, 10, 10, 10],
                filename: 'hasil-form-{{ $form->slug }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // Generate PDF
            html2pdf().set(opt).from(element).save().then(function() {
                window.showAlert('PDF berhasil dibuat dan diunduh.', 'success');
            }).catch(function(error) {
                console.error('PDF generation error:', error);
                window.showAlert('Gagal membuat PDF. Silakan coba lagi.', 'error');
            });
        });

        // Copy form link to clipboard
        if (document.getElementById('copyLinkBtn')) {
            document.getElementById('copyLinkBtn').addEventListener('click', function() {
                const link = this.getAttribute('data-clipboard-text');
                navigator.clipboard.writeText(link).then(function() {
                    window.showAlert('Link form berhasil disalin!', 'success');
                }).catch(function() {
                    window.showAlert('Gagal menyalin link. Silakan coba lagi.', 'error');
                });
            });
        }
    });
</script>
@endpush
