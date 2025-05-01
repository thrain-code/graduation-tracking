@extends('layouts.admin')

@section('title', 'Edit Alumni')

@section('page-title', 'Edit Alumni')

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

<!-- Edit Alumni Form -->
<div class="card rounded-xl p-6 shadow-lg mb-6">
    <h3 class="text-xl font-semibold text-white mb-6">Edit Alumni</h3>
    
    <form action="{{ route('alumni.update', $alumni->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-300 mb-1">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $alumni->nama_lengkap) }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                @error('nama_lengkap')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-1">NIM</label>
                <input type="text" name="nim" value="{{ old('nim', $alumni->nim) }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                @error('nim')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    <option value="laki-laki" {{ old('jenis_kelamin', $alumni->jenis_kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="perempuan" {{ old('jenis_kelamin', $alumni->jenis_kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-1">Tahun Lulus</label>
                <select name="tahun_lulus" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @for ($tahun = date('Y'); $tahun >= 2000; $tahun--)
                        <option value="{{ $tahun }}" {{ old('tahun_lulus', $alumni->tahun_lulus) == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
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
                        <option value="{{ $prodi->id }}" {{ old('prodi_id', $alumni->prodi_id) == $prodi->id ? 'selected' : '' }}>{{ $prodi->prodi_name }}</option>
                    @endforeach
                </select>
                @error('prodi_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $alumni->user->email) }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                @error('email')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-1">Nomor Telepon</label>
                <input type="text" name="number_phone" value="{{ old('number_phone', $alumni->number_phone) }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                @error('number_phone')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-gray-300 mb-1">Alamat</label>
                <textarea rows="3" name="alamat" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">{{ old('alamat', $alumni->alamat) }}</textarea>
                @error('alamat')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Status Fields -->
            <div class="md:col-span-2">
                <label class="block text-gray-300 mb-1">Status</label>
                <select name="status_type" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500" id="statusType">
                    <option value="" {{ old('status_type', $alumni->status ? $alumni->status->type : '') == '' ? 'selected' : '' }}>Tidak Ada Status</option>
                    <option value="bekerja" {{ old('status_type', $alumni->status ? $alumni->status->type : '') == 'bekerja' ? 'selected' : '' }}>Bekerja</option>
                    <option value="kuliah" {{ old('status_type', $alumni->status ? $alumni->status->type : '') == 'kuliah' ? 'selected' : '' }}>Studi Lanjut</option>
                    <option value="wirausaha" {{ old('status_type', $alumni->status ? $alumni->status->type : '') == 'wirausaha' ? 'selected' : '' }}>Wirausaha</option>
                    <option value="mengurus keluarga" {{ old('status_type', $alumni->status ? $alumni->status->type : '') == 'mengurus keluarga' ? 'selected' : '' }}>Mengurus Keluarga</option>
                </select>
                @error('status_type')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Bekerja Fields -->
            <div id="bekerjaFields" class="hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-300 mb-1">Nama Perusahaan</label>
                    <input type="text" name="bekerja_status_nama" value="{{ old('bekerja_status_nama', $alumni->status ? $alumni->status->nama : '') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @error('bekerja_status_nama')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-300 mb-1">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $alumni->status ? $alumni->status->jabatan : '') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @error('jabatan')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-300 mb-1">Jenis Pekerjaan</label>
                    <select name="bekerja_jenis_pekerjaan" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                        <option value="Guru PNS" {{ old('bekerja_jenis_pekerjaan', $alumni->status ? $alumni->status->jenis_pekerjaan : '') == 'Guru PNS' ? 'selected' : '' }}>Guru PNS</option>
                        <option value="Guru Non PNS" {{ old('bekerja_jenis_pekerjaan', $alumni->status ? $alumni->status->jenis_pekerjaan : '') == 'Guru Non PNS' ? 'selected' : '' }}>Guru Non PNS</option>
                        <option value="Tentor/Instruktur/Pengajar" {{ old('bekerja_jenis_pekerjaan', $alumni->status ? $alumni->status->jenis_pekerjaan : '') == 'Tentor/Instruktur/Pengajar' ? 'selected' : '' }}>Tentor/Instruktur/Pengajar</option>
                        <option value="Pengelola Kursus" {{ old('bekerja_jenis_pekerjaan', $alumni->status ? $alumni->status->jenis_pekerjaan : '') == 'Pengelola Kursus' ? 'selected' : '' }}>Pengelola Kursus</option>
                        <option value="Bisnis/Berjualan" {{ old('bekerja_jenis_pekerjaan', $alumni->status ? $alumni->status->jenis_pekerjaan : '') == 'Bisnis/Berjualan' ? 'selected' : '' }}>Bisnis/Berjualan</option>
                        <option value="Karyawan Swasta" {{ old('bekerja_jenis_pekerjaan', $alumni->status ? $alumni->status->jenis_pekerjaan : '') == 'Karyawan Swasta' ? 'selected' : '' }}>Karyawan Swasta</option>
                        <option value="Tidak" {{ old('bekerja_jenis_pekerjaan', $alumni->status ? $alumni->status->jenis_pekerjaan : '') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                    @error('bekerja_jenis_pekerjaan')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-300 mb-1">Gaji</label>
                    <input type="number" name="gaji" value="{{ old('gaji', $alumni->status ? $alumni->status->gaji : '') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @error('gaji')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-300 mb-1">Tahun Mulai</label>
                    <input type="number" name="bekerja_tahun_mulai" value="{{ old('bekerja_tahun_mulai', $alumni->status ? $alumni->status->tahun_mulai : '') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500" min="1900" max="{{ date('Y') }}">
                    @error('bekerja_tahun_mulai')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Kuliah Fields -->
            <div id="kuliahFields" class="hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-300 mb-1">Nama Universitas</label>
                    <input type="text" name="kuliah_status_nama" value="{{ old('kuliah_status_nama', $alumni->status ? $alumni->status->nama : '') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @error('kuliah_status_nama')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-300 mb-1">Jenjang</label>
                    <select name="jenjang" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                        <option value="S1" {{ old('jenjang', $alumni->status ? $alumni->status->jenjang : '') == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ old('jenjang', $alumni->status ? $alumni->status->jenjang : '') == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ old('jenjang', $alumni->status ? $alumni->status->jenjang : '') == 'S3' ? 'selected' : '' }}>S3</option>
                    </select>
                    @error('jenjang')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-300 mb-1">Tahun Mulai</label>
                    <input type="number" name="kuliah_tahun_mulai" value="{{ old('kuliah_tahun_mulai', $alumni->status ? $alumni->status->tahun_mulai : '') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500" min="1900" max="{{ date('Y') }}">
                    @error('kuliah_tahun_mulai')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Wirausaha Fields -->
            <div id="wirausahaFields" class="hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-300 mb-1">Nama Usaha</label>
                    <input type="text" name="wirausaha_status_nama" value="{{ old('wirausaha_status_nama', $alumni->status ? $alumni->status->nama : '') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @error('wirausaha_status_nama')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-300 mb-1">Gaji/Pendapatan</label>
                    <input type="number" name="gaji" value="{{ old('gaji', $alumni->status ? $alumni->status->gaji : '') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                    @error('gaji')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-300 mb-1">Tahun Mulai</label>
                    <input type="number" name="wirausaha_tahun_mulai" value="{{ old('wirausaha_tahun_mulai', $alumni->status ? $alumni->status->tahun_mulai : '') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500" min="1900" max="{{ date('Y') }}">
                    @error('wirausaha_tahun_mulai')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Mengurus Keluarga Fields (Minimal) -->
            <div id="mengurusKeluargaFields" class="hidden md:col-span-2">
                <p class="text-gray-300">Tidak ada detail tambahan untuk status ini.</p>
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
        const wirausahaFields = document.getElementById('wirausahaFields');
        const mengurusKeluargaFields = document.getElementById('mengurusKeluargaFields');

        function toggleFields() {
            // Hide and disable all fields
            bekerjaFields.classList.add('hidden');
            kuliahFields.classList.add('hidden');
            wirausahaFields.classList.add('hidden');
            mengurusKeluargaFields.classList.add('hidden');

            // Disable all inputs in hidden fields
            bekerjaFields.querySelectorAll('input, select').forEach(input => input.disabled = true);
            kuliahFields.querySelectorAll('input, select').forEach(input => input.disabled = true);
            wirausahaFields.querySelectorAll('input, select').forEach(input => input.disabled = true);
            mengurusKeluargaFields.querySelectorAll('input, select').forEach(input => input.disabled = true);

            // Show and enable relevant fields
            if (statusType.value === 'bekerja') {
                bekerjaFields.classList.remove('hidden');
                bekerjaFields.querySelectorAll('input, select').forEach(input => input.disabled = false);
            } else if (statusType.value === 'kuliah') {
                kuliahFields.classList.remove('hidden');
                kuliahFields.querySelectorAll('input, select').forEach(input => input.disabled = false);
            } else if (statusType.value === 'wirausaha') {
                wirausahaFields.classList.remove('hidden');
                wirausahaFields.querySelectorAll('input, select').forEach(input => input.disabled = false);
            } else if (statusType.value === 'mengurus keluarga') {
                mengurusKeluargaFields.classList.remove('hidden');
                mengurusKeluargaFields.querySelectorAll('input, select').forEach(input => input.disabled = false);
            }
        }

        statusType.addEventListener('change', toggleFields);
        toggleFields(); // Trigger on page load
    });
</script>
@endpush
@endsection