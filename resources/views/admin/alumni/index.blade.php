@extends('layouts.admin')

@section('title', 'Data Alumni')

@section('page-title', 'Data Alumni')

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

<!-- Alumni Card -->
<div class="card rounded-xl p-6 shadow-lg mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h3 class="text-xl font-semibold text-white">Daftar Alumni</h3>
        
        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
            <a href="{{ route('alumni.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i> Tambah Alumni
            </a>
            
            <div class="flex gap-2">
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center" disabled>
                    <i class="fas fa-file-import mr-2"></i> Import
                </button>
                
                <button class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg flex items-center justify-center" disabled>
                    <i class="fas fa-file-export mr-2"></i> Export
                </button>
            </div>
        </div>
    </div>
    
    <!-- Filter and Search -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <div class="flex-1">
            <form method="GET" action="{{ route('alumni.index') }}">
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari alumni..." value="{{ request('search') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-2">
            <form method="GET" action="{{ route('alumni.index') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <select name="prodi_id" class="bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500" onchange="this.form.submit()">
                    <option value="">Semua Prodi</option>
                    @foreach ($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->prodi_name }}</option>
                    @endforeach
                </select>
            </form>
            
            <form method="GET" action="{{ route('alumni.index') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="prodi_id" value="{{ request('prodi_id') }}">
                <select name="tahun_lulus" class="bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500" onchange="this.form.submit()">
                    <option value="">Semua Tahun Lulus</option>
                    @foreach ($tahun_lulus_options as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun_lulus') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>
            </form>
            
            <form method="GET" action="{{ route('alumni.index') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="prodi_id" value="{{ request('prodi_id') }}">
                <input type="hidden" name="tahun_lulus" value="{{ request('tahun_lulus') }}">
                <select name="status" class="bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="bekerja" {{ request('status') == 'bekerja' ? 'selected' : '' }}>Bekerja</option>
                    <option value="studi" {{ request('status') == 'studi' ? 'selected' : '' }}>Studi Lanjut</option>
                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Terdata</option>
                </select>
            </form>
        </div>
    </div>
    
    <!-- Alumni Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-800 text-gray-300 text-sm">
                <tr>
                    <th class="px-4 py-3 rounded-tl-lg">Nama Lengkap</th>
                    <th class="px-4 py-3">NIM</th>
                    <th class="px-4 py-3">Jenis Kelamin</th>
                    <th class="px-4 py-3">Tahun Lulus</th>
                    <th class="px-4 py-3">Program Studi</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">No. Telepon</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 rounded-tr-lg text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
                @forelse ($alumnis as $alumni)
                    <tr class="border-b border-slate-700 hover:bg-slate-800/40">
                        <td class="px-4 py-3">{{ $alumni->nama_lengkap }}</td>
                        <td class="px-4 py-3">{{ $alumni->nim }}</td>
                        <td class="px-4 py-3">{{ ucfirst($alumni->jenis_kelamin) }}</td>
                        <td class="px-4 py-3">{{ $alumni->tahun_lulus }}</td>
                        <td class="px-4 py-3">{{ $alumni->prodi->prodi_name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $alumni->user->email ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $alumni->number_phone }}</td>
                        <td class="px-4 py-3">
                            @if ($alumni->status && $alumni->status->is_active)
                                <span class="text-xs px-2 py-1 rounded {{ $alumni->status->isBekerja() ? 'bg-green-500/20 text-green-400' : 'bg-purple-500/20 text-purple-400' }}">
                                    {{ $alumni->status->isBekerja() ? 'Bekerja' : 'Studi Lanjut' }}
                                </span>
                            @else
                                <span class="bg-red-500/20 text-red-400 text-xs px-2 py-1 rounded">Belum Terdata</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('alumni.show', $alumni->id) }}" class="text-blue-400 hover:text-blue-300 mr-2"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('alumni.edit', $alumni->id) }}" class="text-yellow-400 hover:text-yellow-300 mr-2"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('alumni.destroy', $alumni->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Apakah Anda yakin ingin menghapus data alumni ini?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-3 text-center text-gray-400">Tidak ada alumni yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
        <p class="text-sm text-gray-400">Menampilkan {{ $alumnis->count() }} dari {{ $alumnis->total() }} alumni</p>
        <div class="flex">
            {{ $alumnis->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection