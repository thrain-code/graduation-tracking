@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
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
    

</div>



<!-- Quick Actions & Distribusi Pekerjaan -->
<div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
    <div class="card rounded-xl p-6 shadow-lg">
        <h3 class="text-xl font-semibold text-white mb-6">Aksi Cepat</h3>
        
        <div class="grid grid-cols-2 gap-4">
            <a href="#" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
                <div class="w-12 h-12 rounded-full bg-primary-600/20 text-primary-400 flex items-center justify-center mb-3">
                    <i class="fas fa-user-plus text-xl"></i>
                </div>
                <span class="text-white text-sm font-medium">Tambah Alumni</span>
            </a>
            
            <a href="#" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
                <div class="w-12 h-12 rounded-full bg-green-600/20 text-green-400 flex items-center justify-center mb-3">
                    <i class="fas fa-file-import text-xl"></i>
                </div>
                <span class="text-white text-sm font-medium">Import Data</span>
            </a>
            
            <a href="#" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
                <div class="w-12 h-12 rounded-full bg-amber-600/20 text-amber-400 flex items-center justify-center mb-3">
                    <i class="fas fa-chart-bar text-xl"></i>
                </div>
                <span class="text-white text-sm font-medium">Lihat Laporan</span>
            </a>
            
            <a href="#" class="bg-slate-800 hover:bg-slate-700 p-4 rounded-lg flex flex-col items-center justify-center transition duration-300">
                <div class="w-12 h-12 rounded-full bg-purple-600/20 text-purple-400 flex items-center justify-center mb-3">
                    <i class="fas fa-envelope text-xl"></i>
                </div>
                <span class="text-white text-sm font-medium">Kirim Survey</span>
            </a>
        </div>
    </div>
    
</div>
@endsection