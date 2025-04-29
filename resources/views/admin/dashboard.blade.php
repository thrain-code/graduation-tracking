@extends('layouts.admin')

@section('title', 'Dashboard - Admin PTIK Alumni')

@section('page-title', 'Dashboard')

@section('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.01) 100%);
        border: 1px solid rgba(255,255,255,0.1);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .chart-container {
        height: 300px;
        position: relative;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .status-active {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .status-pending {
        background-color: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .status-inactive {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
</style>
@endsection

@section('content')
    <!-- Welcome Message -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Selamat Datang, {{ auth()->user()->name ?? 'Admin' }}!</h1>
        <p class="text-slate-500">Berikut adalah ringkasan data alumni PTIK per {{ date('d F Y') }}</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Alumni -->
        <div class="bg-white rounded-xl shadow-sm p-6 stat-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-slate-500 font-medium text-sm">TOTAL ALUMNI</h3>
                <div class="bg-blue-100 p-2 rounded-lg">
                    <i class="fas fa-user-graduate text-blue-600"></i>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-3xl font-bold text-slate-800">{{ $stats['total_alumni'] }}</p>
                    <p class="text-sm text-slate-500">Total data alumni</p>
                </div>
                <div class="flex items-center text-emerald-500 text-sm">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>{{ $stats['alumni_growth'] }}%</span>
                </div>
            </div>
        </div>
        
        <!-- Bekerja -->
        <div class="bg-white rounded-xl shadow-sm p-6 stat-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-slate-500 font-medium text-sm">ALUMNI BEKERJA</h3>
                <div class="bg-green-100 p-2 rounded-lg">
                    <i class="fas fa-briefcase text-green-600"></i>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-3xl font-bold text-slate-800">{{ $stats['working'] }}%</p>
                    <p class="text-sm text-slate-500">{{ $stats['working_count'] }} alumni</p>
                </div>
                <div class="flex items-center text-emerald-500 text-sm">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>{{ $stats['working_growth'] }}%</span>
                </div>
            </div>
        </div>
        
        <!-- Studi Lanjut -->
        <div class="bg-white rounded-xl shadow-sm p-6 stat-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-slate-500 font-medium text-sm">STUDI LANJUT</h3>
                <div class="bg-purple-100 p-2 rounded-lg">
                    <i class="fas fa-graduation-cap text-purple-600"></i>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-3xl font-bold text-slate-800">{{ $stats['studying'] }}%</p>
                    <p class="text-sm text-slate-500">{{ $stats['studying_count'] }} alumni</p>
                </div>
                <div class="flex items-center text-red-500 text-sm">
                    <i class="fas fa-arrow-down mr-1"></i>
                    <span>{{ $stats['studying_growth'] }}%</span>
                </div>
            </div>
        </div>
        
        <!-- Mencari Kerja -->
        <div class="bg-white rounded-xl shadow-sm p-6 stat-card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-slate-500 font-medium text-sm">MENCARI KERJA</h3>
                <div class="bg-amber-100 p-2 rounded-lg">
                    <i class="fas fa-search text-amber-600"></i>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-3xl font-bold text-slate-800">{{ $stats['searching'] }}%</p>
                    <p class="text-sm text-slate-500">{{ $stats['searching_count'] }} alumni</p>
                </div>
                <div class="flex items-center text-red-500 text-sm">
                    <i class="fas fa-arrow-down mr-1"></i>
                    <span>{{ $stats['searching_growth'] }}%</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Alumni Trend Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-semibold text-slate-800">Tren Status Alumni</h3>
                <div class="flex space-x-2">
                    <button class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 status-filter active" data-period="yearly">Tahunan</button>
                    <button class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 status-filter" data-period="monthly">Bulanan</button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="alumniTrendChart"></canvas>
            </div>
        </div>
        
        <!-- Employment Sectors Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-semibold text-slate-800">Sektor Pekerjaan</h3>
                <div class="flex space-x-2">
                    <select id="sectorYear" class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="sectorChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Alumni & Activities Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Alumni -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-semibold text-slate-800">Alumni Terbaru</h3>
                <a href="{{ route('admin.alumni') }}" class="text-sm text-primary-600 hover:text-primary-700">Lihat Semua</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b">
                            <th class="pb-3 font-medium">Nama</th>
                            <th class="pb-3 font-medium">Angkatan</th>
                            <th class="pb-3 font-medium">Status</th>
                            <th class="pb-3 font-medium">Terdaftar</th>
                            <th class="pb-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_alumni as $alumni)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 font-semibold text-xs mr-3">
                                        {{ strtoupper(substr($alumni->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $alumni->nama }}</p>
                                        <p class="text-xs text-slate-500">{{ $alumni->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">{{ $alumni->tahun_lulus }}</td>
                            <td class="py-3">
                                @if($alumni->getCurrentJob())
                                    <span class="status-badge status-active">Bekerja</span>
                                @elseif($alumni->pendidikanLanjutan()->exists())
                                    <span class="status-badge status-pending">Studi Lanjut</span>
                                @else
                                    <span class="status-badge status-inactive">Mencari Kerja</span>
                                @endif
                            </td>
                            <td class="py-3 text-slate-500">{{ $alumni->created_at->diffForHumans() }}</td>
                            <td class="py-3">
                                <a href="{{ route('admin.alumni.show', $alumni->id) }}" class="text-primary-600 hover:text-primary-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-slate-500">Tidak ada data alumni terbaru</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-semibold text-slate-800">Aktivitas Terbaru</h3>
                <a href="{{ route('admin.logs') }}" class="text-sm text-primary-600 hover:text-primary-700">Lihat Semua</a>
            </div>
            
            <div class="space-y-4">
                @forelse($recent_activities as $activity)
                <div class="flex">
                    <div class="flex-shrink-0 mr-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                            @switch($activity['type'])
                                @case('login')
                                    <i class="fas fa-sign-in-alt"></i>
                                    @break
                                @case('register')
                                    <i class="fas fa-user-plus"></i>
                                    @break
                                @case('update')
                                    <i class="fas fa-edit"></i>
                                    @break
                                @case('delete')
                                    <i class="fas fa-trash-alt"></i>
                                    @break
                                @default
                                    <i class="fas fa-circle"></i>
                            @endswitch
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-slate-800">{{ $activity['message'] }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $activity['time'] }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <p class="text-slate-500">Tidak ada aktivitas terbaru</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Quick Access / Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Top Perusahaan -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Top Perusahaan</h3>
            <div class="space-y-3">
                @foreach($top_companies as $company)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-semibold text-xs mr-2">
                            {{ strtoupper(substr($company['name'], 0, 1)) }}
                        </div>
                        <span class="text-sm text-slate-800">{{ $company['name'] }}</span>
                    </div>
                    <span class="text-sm font-medium text-slate-700">{{ $company['count'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Top Institusi Pendidikan Lanjut -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Top Institusi Pendidikan</h3>
            <div class="space-y-3">
                @foreach($top_institutions as $institution)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-semibold text-xs mr-2">
                            {{ strtoupper(substr($institution['name'], 0, 1)) }}
                        </div>
                        <span class="text-sm text-slate-800">{{ $institution['name'] }}</span>
                    </div>
                    <span class="text-sm font-medium text-slate-700">{{ $institution['count'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Domisili / Lokasi -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Domisili Alumni</h3>
            <div class="space-y-3">
                @foreach($top_locations as $location)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 mr-2">
                            <i class="fas fa-map-marker-alt text-slate-500 text-xs"></i>
                        </div>
                        <span class="text-sm text-slate-800">{{ $location['name'] }}</span>
                    </div>
                    <span class="text-sm font-medium text-slate-700">{{ $location['count'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Quick Tasks -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Tugas Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.alumni.create') }}" class="flex items-center p-3 rounded-lg hover:bg-slate-50 transition">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3">
                        <i class="fas fa-user-plus text-xs"></i>
                    </div>
                    <span class="text-sm text-slate-800">Tambah Alumni Baru</span>
                </a>
                
                <a href="{{ route('admin.survey') }}" class="flex items-center p-3 rounded-lg hover:bg-slate-50 transition">
                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-3">
                        <i class="fas fa-chart-pie text-xs"></i>
                    </div>
                    <span class="text-sm text-slate-800">Buat Survey Baru</span>
                </a>
                
                <a href="{{ route('admin.exportData') }}" class="flex items-center p-3 rounded-lg hover:bg-slate-50 transition">
                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mr-3">
                        <i class="fas fa-file-export text-xs"></i>
                    </div>
                    <span class="text-sm text-slate-800">Export Data Alumni</span>
                </a>
                
                <a href="{{ route('admin.reports.generate') }}" class="flex items-center p-3 rounded-lg hover:bg-slate-50 transition">
                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mr-3">
                        <i class="fas fa-file-pdf text-xs"></i>
                    </div>
                    <span class="text-sm text-slate-800">Buat Laporan</span>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Alumni Trend Chart
        const trendsCtx = document.getElementById('alumniTrendChart').getContext('2d');
        
        const trendsData = {
            yearly: {
                labels: @json($trends['yearly']['labels']),
                bekerja: @json($trends['yearly']['working']),
                studi: @json($trends['yearly']['studying']),
                mencari: @json($trends['yearly']['searching'])
            },
            monthly: {
                labels: @json($trends['monthly']['labels']),
                bekerja: @json($trends['monthly']['working']),
                studi: @json($trends['monthly']['studying']),
                mencari: @json($trends['monthly']['searching'])
            }
        };
        
        let activePeriod = 'yearly';
        
        const trendsChart = new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: trendsData[activePeriod].labels,
                datasets: [
                    {
                        label: 'Bekerja',
                        data: trendsData[activePeriod].bekerja,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Studi Lanjut',
                        data: trendsData[activePeriod].studi,
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Mencari Kerja',
                        data: trendsData[activePeriod].mencari,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 2,
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
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(15, 23, 42, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#e2e8f0',
                        padding: 10,
                        displayColors: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: {
                            drawBorder: false,
                            color: 'rgba(226, 232, 240, 0.3)'
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
        
        // Handle period filter clicks
        document.querySelectorAll('.status-filter').forEach(button => {
            button.addEventListener('click', function() {
                const period = this.getAttribute('data-period');
                
                // Toggle active class
                document.querySelectorAll('.status-filter').forEach(btn => {
                    btn.classList.remove('active', 'bg-primary-500', 'text-white');
                    btn.classList.add('bg-slate-100', 'text-slate-600');
                });
                
                this.classList.remove('bg-slate-100', 'text-slate-600');
                this.classList.add('active', 'bg-primary-500', 'text-white');
                
                // Update chart data
                activePeriod = period;
                trendsChart.data.labels = trendsData[period].labels;
                trendsChart.data.datasets[0].data = trendsData[period].bekerja;
                trendsChart.data.datasets[1].data = trendsData[period].studi;
                trendsChart.data.datasets[2].data = trendsData[period].mencari;
                trendsChart.update();
            });
        });
        
        // Initialize with active class
        document.querySelector('.status-filter.active').classList.remove('bg-slate-100', 'text-slate-600');
        document.querySelector('.status-filter.active').classList.add('bg-primary-500', 'text-white');
        
        // Sector Chart
        const sectorCtx = document.getElementById('sectorChart').getContext('2d');
        
        const sectorData = @json($sectors);
        const sectorYear = document.getElementById('sectorYear');
        
        const sectorChart = new Chart(sectorCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(sectorData[sectorYear.value]),
                datasets: [{
                    data: Object.values(sectorData[sectorYear.value]),
                    backgroundColor: [
                        '#3b82f6', // blue
                        '#8b5cf6', // purple
                        '#10b981', // green
                        '#f59e0b', // amber
                        '#ef4444'  // red
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#e2e8f0',
                        padding: 10,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw}%`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
        
        // Handle sector year change
        sectorYear.addEventListener('change', function() {
            const year = this.value;
            
            sectorChart.data.labels = Object.keys(sectorData[year]);
            sectorChart.data.datasets[0].data = Object.values(sectorData[year]);
            sectorChart.update();
        });
    });
</script>
@endsection