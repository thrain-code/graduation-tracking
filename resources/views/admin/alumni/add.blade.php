@extends('layouts.admin')

@section('title', 'Tambah Alumni')

@section('page-title', 'Tambah Alumni')

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

<!-- Add Alumni Form -->
<div class="card rounded-xl p-6 shadow-lg mb-6">
    <h3 class="text-xl font-semibold text-white mb-6">Tambah Alumni Baru</h3>
    
    <form action="{{ route('alumni.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-300 mb-1">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                @error('nama_lengkap')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-1">NIM</label>
                <input type="text" name="nim" value="{{ old('nim') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                @error('nim')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    <option value="laki-laki" {{ old('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-1">Tahun Lulus</label>
                <select name="tahun_lulus" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @for ($tahun = date('Y'); $tahun >= 2000; $tahun--)
                        <option value="{{ $tahun }}" {{ old('tahun_lulus') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endfor
                </select>
                @error('tahun_lulus')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-1">Program Studi</label>
                <select name="prodi_id" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @foreach ($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->prodi_name }}</option>
                    @endforeach
                </select>
                @error('prodi_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-1">Password</label>
                <input type="password" name="password" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                @error('password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-1">Nomor Telepon</label>
                <input type="text" name="number_phone" value="{{ old('number_phone') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                @error('number_phone')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-gray-300 mb-1">Alamat</label>
                <textarea rows="3" name="alamat" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Status Fields -->
            <div class="md:col-span-2">
                <label class="block text-gray-300 mb-1">Status</label>
                <select name="status_type" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500" id="statusType">
                    <option value="" {{ old('status_type') == '' ? 'selected' : '' }}>Tidak Ada Status</option>
                    <option value="bekerja" {{ old('status_type') == 'bekerja' ? 'selected' : '' }}>Bekerja</option>
                    <option value="kuliah" {{ old('status_type') == 'kuliah' ? 'selected' : '' }}>Studi Lanjut</option>
                </select>
                @error('status_type')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div id="bekerjaFields" class="hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-300 mb-1">Nama Perusahaan</label>
                    <input type="text" name="status_nama" value="{{ old('status_nama') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @error('status_nama')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-300 mb-1">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @error('jabatan')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-300 mb-1">Gaji</label>
                    <input type="number" name="gaji" value="{{ old('gaji') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @error('gaji')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-300 mb-1">Tahun Mulai</label>
                    <input type="number" name="tahun_mulai" value="{{ old('tahun_mulai') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @error('tahun_mulai')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div id="kuliahFields" class="hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-300 mb-1">Nama Universitas</label>
                    <input type="text" name="status_nama" value="{{ old('status_nama') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @error('status_nama')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-300 mb-1">Jenjang</label>
                    <select name="jenjang" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                        <option value="S1" {{ old('jenjang') == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ old('jenjang') == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ old('jenjang') == 'S3' ? 'selected' : '' }}>S3</option>
                    </select>
                    @error('jenjang')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-300 mb-1">Tahun Mulai</label>
                    <input type="number" name="tahun_mulai" value="{{ old('tahun_mulai') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @error('tahun_mulai')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="flex justify-end">
            <a href="{{ route('alumni.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg mr-2">
                Batal
            </a>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                Simpan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusType = document.getElementById('statusType');
        const bekerjaFields = document.getElementById('bekerjaFields');
        const kuliahFields = document.getElementById('kuliahFields');
        
        if (statusType && bekerjaFields && kuliahFields) {
            statusType.addEventListener('change', function() {
                bekerjaFields.classList.add('hidden');
                kuliahFields.classList.add('hidden');
                
                if (this.value === 'bekerja') {
                    bekerjaFields.classList.remove('hidden');
                } else if (this.value === 'kuliah') {
                    kuliahFields.classList.remove('hidden');
                }
            });
            
            // Trigger change on load to show fields if old input exists
            statusType.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
@endsection