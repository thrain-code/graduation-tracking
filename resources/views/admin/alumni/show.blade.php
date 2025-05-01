@extends('layouts.admin')

@section('title', 'Detail Alumni')

@section('page-title', 'Detail Alumni')

@section('content')
<!-- Flash Messages -->
@if (session('success'))
    <div class="bg-green-500/20 text-green-400 p-4 rounded-lg mb-4">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="bg-red-500/20 text-red-400 p-4 rounded-lg mb-4">
        {{ session('error') }}
    </div>
@endif

<!-- Alumni Details -->
<div class="card rounded-xl p-6 shadow-lg mb-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-white">Detail Alumni</h3>
        <a href="{{ route('alumni.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg">
            Kembali
        </a>
    </div>
    
    <div class="bg-slate-900 rounded-lg p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="md:w-2/3">
                <h4 class="text-lg font-medium text-white mb-3">Informasi Pribadi</h4>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                    <div>
                        <dt class="text-sm text-gray-400">Nama Lengkap</dt>
                        <dd class="text-white">{{ $alumni->nama_lengkap ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-400">NIM</dt>
                        <dd class="text-white">{{ $alumni->nim ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-400">Jenis Kelamin</dt>
                        <dd class="text-white">{{ $alumni->jenis_kelamin ? ucfirst($alumni->jenis_kelamin) : 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-400">Tahun Lulus</dt>
                        <dd class="text-white">{{ $alumni->tahun_lulus ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-400">Program Studi</dt>
                        <dd class="text-white">{{ $alumni->prodi->prodi_name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-400">Email</dt>
                        <dd class="text-white">{{ $alumni->user->email ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-400">Nomor Telepon</dt>
                        <dd class="text-white">{{ $alumni->number_phone ?? 'N/A' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm text-gray-400">Alamat</dt>
                        <dd class="text-white">{{ $alumni->alamat ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
    
    <!-- Status Section -->
    <div class="mb-6">
        <h4 class="text-lg font-medium text-white mb-3">Status Saat Ini</h4>
        
        @if ($alumni->status)
            <div class="bg-slate-800 p-4 rounded-lg">
                <div class="flex justify-between">
                    <div>
                        @if ($alumni->status->type === 'bekerja')
                            <h5 class="font-medium text-white">Bekerja di: {{ $alumni->status->nama ?? 'N/A' }}</h5>
                            <p class="text-primary-400">Jabatan: {{ $alumni->status->jabatan ?? 'N/A' }}</p>
                            <p class="text-primary-400">Jenis Pekerjaan: {{ $alumni->status->jenis_pekerjaan ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-400">Sejak {{ $alumni->status->tahun_mulai ?? 'N/A' }}</p>
                        @elseif ($alumni->status->type === 'kuliah')
                            <h5 class="font-medium text-white">Kuliah di: {{ $alumni->status->nama ?? 'N/A' }}</h5>
                            <p class="text-primary-400">Jenjang: {{ $alumni->status->jenjang ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-400">Sejak {{ $alumni->status->tahun_mulai ?? 'N/A' }}</p>
                        @elseif ($alumni->status->type === 'wirausaha')
                            <h5 class="font-medium text-white">Wirausaha: {{ $alumni->status->nama ?? 'N/A' }}</h5>
                            <p class="text-primary-400">Jenis Usaha: {{ $alumni->status->jenis_pekerjaan ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-400">Sejak {{ $alumni->status->tahun_mulai ?? 'N/A' }}</p>
                        @elseif ($alumni->status->type === 'mengurus keluarga')
                            <h5 class="font-medium text-white">Mengurus Keluarga</h5>
                            <p class="text-sm text-gray-400">Tidak ada detail tambahan untuk status ini.</p>
                        @else
                            <p class="text-gray-400">Status tidak diketahui.</p>
                        @endif
                    </div>
                    <div class="text-right">
                        @if (in_array($alumni->status->type, ['bekerja', 'wirausaha']) && $alumni->status->gaji)
                            <p class="text-green-400 font-medium">Rp {{ number_format($alumni->status->gaji, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="bg-slate-800 p-4 rounded-lg text-gray-400">
                Tidak ada status aktif saat ini.
            </div>
        @endif
    </div>
</div>
@endsection
