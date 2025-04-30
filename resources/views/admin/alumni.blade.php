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
            <button id="openAddAlumniModal" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i> Tambah Alumni
            </button>
            
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
                            <button class="text-blue-400 hover:text-blue-300 mr-2 show-detail-btn" data-id="{{ $alumni->id }}"><i class="fas fa-eye"></i></button>
                            <button class="text-red-400 hover:text-red-300 delete-btn" data-id="{{ $alumni->id }}"><i class="fas fa-trash"></i></button>
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



@section('modals')
<!-- Modal - Add Alumni -->
<div id="addAlumniModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative top-20 mx-auto max-w-4xl bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700 max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-white">Tambah Alumni Baru</h3>
            <button id="closeAddAlumniModal" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
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
                        <option value="">Tidak Ada Status</option>
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
                <button type="button" id="cancelAddAlumni" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg mr-2">
                    Batal
                </button>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal - Show Detail Alumni -->
<div id="showAlumniModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
</div>

<!-- Modal - Delete Confirmation -->
<div id="deleteConfirmModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative top-1/4 mx-auto max-w-md bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-400">Apakah Anda yakin ingin menghapus data alumni ini? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        
        <div class="flex justify-center gap-3">
            <button id="cancelDelete" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg">
                Batal
            </button>
            <form id="deleteAlumniForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" id="confirmDelete" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add Alumni Modal
        const openAddAlumniModal = document.getElementById('openAddAlumniModal');
        const addAlumniModal = document.getElementById('addAlumniModal');
        const closeAddAlumniModal = document.getElementById('closeAddAlumniModal');
        const cancelAddAlumni = document.getElementById('cancelAddAlumni');
        
        if (openAddAlumniModal && addAlumniModal) {
            openAddAlumniModal.addEventListener('click', function() {
                addAlumniModal.classList.remove('hidden');
            });
        }
        
        if (closeAddAlumniModal) {
            closeAddAlumniModal.addEventListener('click', function() {
                addAlumniModal.classList.add('hidden');
            });
        }
        
        if (cancelAddAlumni) {
            cancelAddAlumni.addEventListener('click', function() {
                addAlumniModal.classList.add('hidden');
            });
        }
        
        // Show Detail Modal (AJAX)
        const showDetailButtons = document.querySelectorAll('.show-detail-btn');
        const showAlumniModal = document.getElementById('showAlumniModal');
        
        if (showDetailButtons.length > 0 && showAlumniModal) {
            showDetailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const alumniId = this.getAttribute('data-id');
                    fetch(`{{ url('alumni') }}/${alumniId}`, {
                        headers: {
                            'Accept': 'text/html',
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Alumni tidak ditemukan');
                        }
                        return response.text();
                    })
                    .then(data => {
                        showAlumniModal.innerHTML = data;
                        showAlumniModal.classList.remove('hidden');
                        
                        // Reattach modal close events
                        const newCloseBtn = showAlumniModal.querySelector('#closeShowAlumniModal');
                        const newCancelBtn = showAlumniModal.querySelector('#cancelShowAlumni');
                        
                        if (newCloseBtn) {
                            newCloseBtn.addEventListener('click', () => showAlumniModal.classList.add('hidden'));
                        }
                        if (newCancelBtn) {
                            newCancelBtn.addEventListener('click', () => showAlumniModal.classList.add('hidden'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat detail alumni: ' + error.message);
                    });
                });
            });
        }
        
        // Delete Confirmation Modal
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const deleteConfirmModal = document.getElementById('deleteConfirmModal');
        const cancelDelete = document.getElementById('cancelDelete');
        const deleteAlumniForm = document.getElementById('deleteAlumniForm');
        
        if (deleteButtons.length > 0 && deleteConfirmModal && deleteAlumniForm) {
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const alumniId = this.getAttribute('data-id');
                    deleteAlumniForm.action = `{{ url('alumni') }}/${alumniId}`;
                    deleteConfirmModal.classList.remove('hidden');
                });
            });
        }
        
        if (cancelDelete) {
            cancelDelete.addEventListener('click', function() {
                deleteConfirmModal.classList.add('hidden');
                deleteAlumniForm.action = '';
            });
        }
        
        // Status Type Toggle
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
        }
    });
</script>
@endpush