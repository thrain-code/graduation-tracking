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
                <li>Isi data alumni sesuai format pada template (mulai dari baris 4).</li>
                <li>Kolom dengan header berwarna <span class="text-primary-500 font-semibold">biru tua</span> adalah kolom wajib diisi.</li>
                <li>Pastikan data NIM dan Email bersifat unik/belum ada di sistem.</li>
                <li>Upload file yang sudah diisi dengan tombol import.</li>
            </ol>
        </div>
        
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <a href="{{ route('alumni.template') }}" class="inline-flex items-center justify-center bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-download mr-2"></i> Download Template
            </a>
        </div>
    </div>
    
    <div class="bg-slate-800/50 rounded-xl p-6 shadow-lg">
        <h2 class="text-xl font-bold text-white mb-4">Upload File</h2>
        
        <form action="{{ route('alumni.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label class="block text-slate-300 mb-2">File Excel/CSV</label>
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="file" name="file" id="file-upload" accept=".xlsx,.xls,.csv" class="hidden" required />
                            <label for="file-upload" class="bg-slate-700 hover:bg-slate-600 text-slate-300 w-full px-4 py-2 rounded-lg border border-slate-600 cursor-pointer flex items-center justify-between">
                                <span id="file-name">Pilih File Excel/CSV</span>
                                <i class="fas fa-upload"></i>
                            </label>
                        </div>
                        @error('file')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center">
                        <i class="fas fa-file-import mr-2"></i> Import Data
                    </button>
                </div>
            </div>
        </form>
    </div>
    
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
                        @foreach(session('failures') as $failure)
                            <tr>
                                <td class="py-2 px-4 text-slate-300 border-b border-slate-700">{{ $failure->row() }}</td>
                                <td class="py-2 px-4 text-slate-300 border-b border-slate-700">{{ $failure->attribute() }}</td>
                                <td class="py-2 px-4 text-red-400 border-b border-slate-700">{{ $failure->errors()[0] }}</td>
                                <td class="py-2 px-4 text-slate-300 border-b border-slate-700">{{ $failure->values()[$failure->attribute()] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Display selected filename
    document.getElementById('file-upload').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih File Excel/CSV';
        document.getElementById('file-name').textContent = fileName;
    });
</script>
@endpush