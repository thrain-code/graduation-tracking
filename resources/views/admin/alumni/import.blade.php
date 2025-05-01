@extends('layouts.admin')

@section('title', 'Import Data Alumni')
@section('page-title', 'Import Data Alumni')

@section('content')
<div class="bg-slate-800/50 rounded-xl p-6 shadow-lg">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-white mb-4">Panduan Import Data</h2>
        <div class="bg-slate-700/50 rounded-lg p-4 mb-4">
            <ol class="list-decimal list-inside text-slate-300 space-y-2">
                <li>Download template Excel terlebih dahulu menggunakan tombol di bawah.</li>
                <li>Isi data alumni sesuai format pada template (mulai dari baris 7, setelah baris contoh).</li>
                <li>Kolom dengan tanda <span class="text-red-500 font-semibold">*</span> adalah kolom wajib diisi.</li>
                <li>Pastikan data NIM dan Email bersifat unik/belum ada di sistem.</li>
                <li>Format file yang didukung: XLSX, XLS, CSV (maksimal 10MB).</li>
                <li>Pada template terdapat pengelompokan data berdasarkan warna untuk memudahkan pengisian.</li>
            </ol>
        </div>
        
        <div class="bg-primary-900/30 rounded-lg p-4 mb-6 border-l-4 border-primary-500">
            <h3 class="text-primary-400 font-semibold mb-2">Tips Import Data</h3>
            <ul class="list-disc list-inside text-slate-300 space-y-1">
                <li>Gunakan dropdown yang tersedia untuk mengisi data yang memiliki opsi terbatas.</li>
                <li>Untuk data gaji, masukkan hanya angka tanpa titik, koma, atau simbol mata uang.</li>
                <li>Jika ada banyak data, sebaiknya bagi menjadi beberapa batch untuk menghindari timeout.</li>
                <li>Perhatikan pesan error jika import gagal untuk perbaikan data.</li>
            </ul>
        </div>
        
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <a href="{{ route('alumni.template') }}" class="inline-flex items-center justify-center bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-download mr-2"></i> Download Template Terbaru
            </a>
            <a href="{{ route('alumni.index') }}" class="inline-flex items-center justify-center bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
    
    <div class="bg-slate-800/50 rounded-xl p-6 shadow-lg mb-8">
        <h2 class="text-xl font-bold text-white mb-4">Upload File</h2>
        
        <form action="{{ route('alumni.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf
            <div class="mb-6">
                <label class="block text-slate-300 mb-2">File Excel/CSV</label>
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <div class="flex-1 w-full">
                        <div class="relative">
                            <input type="file" name="file" id="file-upload" accept=".xlsx,.xls,.csv" class="hidden" required />
                            <label for="file-upload" class="bg-slate-700 hover:bg-slate-600 text-slate-300 w-full px-4 py-2 rounded-lg border border-slate-600 cursor-pointer flex items-center justify-between">
                                <span id="file-name">Pilih File Excel/CSV</span>
                                <i class="fas fa-upload"></i>
                            </label>
                            <div id="file-info" class="text-xs text-slate-400 mt-1 hidden">
                                <span id="file-size"></span> | <span id="file-type"></span>
                            </div>
                        </div>
                        @error('file')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" id="submitBtn" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center">
                        <i class="fas fa-file-import mr-2"></i> Import Data
                    </button>
                </div>
            </div>
        </form>
        
        <div id="importProgress" class="hidden mt-4">
            <div class="flex items-center space-x-2 mb-2">
                <span class="text-slate-300">Memproses import...</span>
                <div class="animate-spin rounded-full h-4 w-4 border-t-2 border-b-2 border-white"></div>
            </div>
            <div class="w-full bg-slate-700 rounded-full h-2.5">
                <div id="progressBar" class="bg-primary-500 h-2.5 rounded-full" style="width: 0%"></div>
            </div>
            <p class="text-xs text-slate-400 mt-1">Mohon jangan menutup halaman ini selama proses import berlangsung</p>
        </div>
    </div>
    
    @if(session('import_summary'))
    <div class="bg-green-900/20 border border-green-700 rounded-lg p-4 mb-8">
        <h3 class="text-green-400 font-semibold text-lg mb-2">Ringkasan Import</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-slate-800/60 rounded-lg p-3 text-center">
                <span class="text-slate-400 text-sm">Total Baris</span>
                <p class="text-white text-xl font-bold">{{ session('import_summary.total_rows') }}</p>
            </div>
            <div class="bg-slate-800/60 rounded-lg p-3 text-center">
                <span class="text-slate-400 text-sm">Berhasil Import</span>
                <p class="text-green-400 text-xl font-bold">{{ session('import_summary.imported') }}</p>
            </div>
            <div class="bg-slate-800/60 rounded-lg p-3 text-center">
                <span class="text-slate-400 text-sm">Gagal Import</span>
                <p class="text-red-400 text-xl font-bold">{{ session('import_summary.failed') }}</p>
            </div>
            <div class="bg-slate-800/60 rounded-lg p-3 text-center">
                <span class="text-slate-400 text-sm">Waktu Import</span>
                <p class="text-white text-md font-medium">{{ session('import_summary.date') }}</p>
            </div>
        </div>
    </div>
    @endif
    
    @if(session('failures'))
        <div class="mt-8">
            <h3 class="text-xl font-bold text-red-500 mb-4">Data Yang Gagal Diimpor</h3>
            <div class="bg-slate-700/50 rounded-lg p-4 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 text-left text-slate-300 border-b border-slate-600">Baris</th>
                            <th class="py-2 px-4 text-left text-slate-300 border-b border-slate-600">Kolom</th>
                            <th class="py-2 px-4 text-left text-slate-300 border-b border-slate-600">Error</th>
                            <th class="py-2 px-4 text-left text-slate-300 border-b border-slate-600">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('failures') as $rowId => $failure)
                            @foreach($failure['errors'] as $column => $error)
                                <tr>
                                    <td class="py-2 px-4 text-slate-300 border-b border-slate-700">{{ $failure['row'] }}</td>
                                    <td class="py-2 px-4 text-slate-300 border-b border-slate-700">{{ $column }}</td>
                                    <td class="py-2 px-4 text-red-400 border-b border-slate-700">{{ $error }}</td>
                                    <td class="py-2 px-4 text-slate-300 border-b border-slate-700">
                                        {{ $failure['values'][$column] ?? 'Tidak ada nilai' }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 bg-yellow-900/20 border border-yellow-700 rounded-lg p-4">
                <h4 class="text-yellow-500 font-semibold mb-2">Panduan Perbaikan</h4>
                <ul class="list-disc list-inside text-slate-300 space-y-1">
                    <li>Pastikan NIM bersifat unik dan belum ada di sistem.</li>
                    <li>Pastikan format email valid dan belum terdaftar di sistem.</li>
                    <li>Untuk jenis kelamin, gunakan "Laki-laki" atau "Perempuan".</li>
                    <li>Untuk status, gunakan "Ya" atau "Tidak".</li>
                    <li>Untuk tahun, pastikan format angka dan dalam rentang yang valid.</li>
                </ul>
            </div>
        </div>
    @endif
    
    <div class="bg-slate-700/30 rounded-lg p-4 mt-8">
        <h3 class="text-white font-bold mb-3">Informasi Tambahan</h3>
        <ul class="list-disc list-inside text-slate-300 space-y-2">
            <li>NIM dan Email alumni harus bersifat unik untuk menghindari duplikasi data.</li>
            <li>Password default untuk login alumni adalah NIM mereka.</li>
            <li>Sistem akan secara otomatis membuat email berdasarkan nama jika tidak diisi.</li>
            <li>Data yang sudah diimport tidak dapat diubah secara massal, hanya dapat diubah satu per satu.</li>
            <li>Untuk import dengan jumlah data besar, disarankan membaginya menjadi beberapa batch.</li>
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Display selected filename and file info
    document.getElementById('file-upload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Display file name
            document.getElementById('file-name').textContent = file.name;
            
            // Show file info
            const fileInfo = document.getElementById('file-info');
            fileInfo.classList.remove('hidden');
            
            // Format file size
            const fileSize = (file.size / 1024).toFixed(2);
            let formattedSize = '';
            if (fileSize >= 1024) {
                formattedSize = (fileSize / 1024).toFixed(2) + ' MB';
            } else {
                formattedSize = fileSize + ' KB';
            }
            
            document.getElementById('file-size').textContent = formattedSize;
            
            // Display file type
            const fileExt = file.name.split('.').pop().toUpperCase();
            document.getElementById('file-type').textContent = fileExt + ' File';
        } else {
            document.getElementById('file-name').textContent = 'Pilih File Excel/CSV';
            document.getElementById('file-info').classList.add('hidden');
        }
    });
    
    // Show progress bar when form is submitted
    document.getElementById('importForm').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('file-upload');
        if (fileInput.files.length > 0) {
            // Hide submit button and show progress
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengunggah...';
            document.getElementById('importProgress').classList.remove('hidden');
            
            // Simulate progress for better UX (actual progress can't be tracked)
            let progress = 0;
            const progressBar = document.getElementById('progressBar');
            
            const interval = setInterval(function() {
                if (progress >= 90) {
                    clearInterval(interval);
                } else {
                    progress += Math.random() * 5;
                    progressBar.style.width = Math.min(progress, 90) + '%';
                }
            }, 300);
        }
    });
</script>
@endpush