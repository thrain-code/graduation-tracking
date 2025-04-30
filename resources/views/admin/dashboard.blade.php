@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Alumni Card -->
    <div class="card rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-white">Total Alumni</h3>
            <div class="w-12 h-12 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-white mb-1">{{ $totalAlumni }}</p>
        <p class="text-slate-400 text-sm">+{{ $newAlumniCount }} alumni baru bulan ini</p>
    </div>
    
    <!-- Bekerja Card -->
    <div class="card rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-white">Status Bekerja</h3>
            <div class="w-12 h-12 rounded-lg bg-green-500/20 text-green-400 flex items-center justify-center">
                <i class="fas fa-briefcase text-xl"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-white mb-1">{{ $bekerjaPercent }}%</p>
        <p class="text-slate-400 text-sm">{{ $bekerjaCompare > 0 ? '+' : '' }}{{ $bekerjaCompare }}% dibanding tahun lalu</p>
    </div>
    
    <!-- Studi Lanjut Card -->
    <div class="card rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-white">Studi Lanjut</h3>
            <div class="w-12 h-12 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center">
                <i class="fas fa-graduation-cap text-xl"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-white mb-1">{{ $studiPercent }}%</p>
        <p class="text-slate-400 text-sm">{{ $studiCompare > 0 ? '+' : '' }}{{ $studiCompare }}% dibanding tahun lalu</p>
    </div>
    
    <!-- Mencari Kerja Card -->
    <div class="card rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-white">Mencari Kerja</h3>
            <div class="w-12 h-12 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center">
                <i class="fas fa-search text-xl"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-white mb-1">{{ $mencariKerjaPercent }}%</p>
        <p class="text-slate-400 text-sm">{{ $mencariKerjaCompare > 0 ? '+' : '' }}{{ $mencariKerjaCompare }}% dibanding tahun lalu</p>
    </div>
</div>

<!-- Alumni Terbaru -->
<div class="card rounded-xl p-6 shadow-lg mb-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-white">Alumni Terbaru</h3>
        <a href="{{ route('admin.alumni') }}" class="text-primary-400 hover:text-primary-300 text-sm">Lihat Semua</a>
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
                @forelse($recentAlumni as $alumni)
                <tr class="border-t border-slate-800">
                    <td class="py-4 text-white">{{ $alumni->nama_lengkap }}</td>
                    <td class="py-4 text-slate-300">{{ $alumni->nim }}</td>
                    <td class="py-4 text-slate-300">{{ $alumni->tahun_lulus }}</td>
                    <td class="py-4">
                        @if($alumni->status === 'bekerja')
                        <span class="bg-green-500/20 text-green-400 px-2 py-1 rounded-full text-xs">Bekerja</span>
                        @elseif($alumni->status === 'studi')
                        <span class="bg-purple-500/20 text-purple-400 px-2 py-1 rounded-full text-xs">Studi Lanjut</span>
                        @else
                        <span class="bg-amber-500/20 text-amber-400 px-2 py-1 rounded-full text-xs">Mencari Kerja</span>
                        @endif
                    </td>
                    <td class="py-4 text-right">
                        <a href="{{ route('admin.alumni.show', $alumni->id) }}" class="text-primary-400 hover:text-primary-300 px-2"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.alumni.edit', $alumni->id) }}" class="text-amber-400 hover:text-amber-300 px-2"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 text-center text-slate-400">Belum ada data alumni</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions & Distribusi Pekerjaan -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card rounded-xl p-6 shadow-lg">
        <h3 class="text-xl font-semibold text-white mb-6">Aksi Cepat</h3>
        
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('admin.alumni.create') }}" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
                <div class="w-12 h-12 rounded-full bg-primary-600/20 text-primary-400 flex items-center justify-center mb-3">
                    <i class="fas fa-user-plus text-xl"></i>
                </div>
                <span class="text-white text-sm font-medium">Tambah Alumni</span>
            </a>
            
            <a href="{{ route('admin.alumni.import') }}" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
                <div class="w-12 h-12 rounded-full bg-green-600/20 text-green-400 flex items-center justify-center mb-3">
                    <i class="fas fa-file-import text-xl"></i>
                </div>
                <span class="text-white text-sm font-medium">Import Data</span>
            </a>
            
            <a href="{{ route('admin.reports') }}" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
                <div class="w-12 h-12 rounded-full bg-amber-600/20 text-amber-400 flex items-center justify-center mb-3">
                    <i class="fas fa-chart-bar text-xl"></i>
                </div>
                <span class="text-white text-sm font-medium">Lihat Laporan</span>
            </a>
            
            <a href="{{ route('admin.surveys') }}" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
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
            @foreach($jobSectors as $sector)
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full bg-{{ $sector['color'] }}-500 mr-3"></div>
                <div class="flex-1">
                    <div class="flex justify-between mb-1">
                        <span class="text-slate-300">{{ $sector['name'] }}</span>
                        <span class="text-slate-300">{{ $sector['percent'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-700 rounded-full h-2">
                        <div class="bg-{{ $sector['color'] }}-500 h-2 rounded-full" style="width: {{ $sector['percent'] }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection