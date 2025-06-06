@extends('layouts.admin')

@section('title', 'Program Studi')

@section('page-title', 'Program Studi')

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

<!-- Prodi Card -->
<div class="card rounded-xl p-6 shadow-lg mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h3 class="text-xl font-semibold text-white">Daftar Program Studi</h3>

        <button id="openAddProdiModal" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Program Studi
        </button>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <form method="GET" action="{{ route('prodi.index') }}">
            <div class="relative">
                <input type="text" name="search" placeholder="Cari program studi..." value="{{ request('search') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:border-primary-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </form>
    </div>

    <!-- Prodi Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-800 text-gray-300 text-sm">
                <tr>
                    <th class="px-4 py-3 rounded-tl-lg">#</th>
                    <th class="px-4 py-3">Nama Program Studi</th>
                    <th class="px-4 py-3 rounded-tr-lg text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
                @forelse ($prodis as $prodi)
                <tr class="border-b border-slate-700 hover:bg-slate-800/40">
                    <td class="px-4 py-3">{{ $prodis->firstItem() + $loop->index }}</td>
                    <td class="px-4 py-3">{{ $prodi->prodi_name }}</td>
                    <td class="px-4 py-3 text-right">
                        <button class="text-blue-400 hover:text-blue-300 mr-2 edit-prodi-btn" data-id="{{ $prodi->id }}" data-name="{{ $prodi->prodi_name }}"><i class="fas fa-edit"></i></button>
                        <button class="text-red-400 hover:text-red-300 delete-prodi-btn" data-id="{{ $prodi->id }}"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-3 text-center text-gray-400">Tidak ada program studi yang ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
        <p class="text-sm text-gray-400">Menampilkan {{ $prodis->count() }} dari {{ $prodis->total() }} program studi</p>
        <div class="flex">
            {{ $prodis->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection

@section('modals')
<!-- Modal - Add Prodi -->
<div id="addProdiModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative top-1/4 mx-auto max-w-md bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-white">Tambah Program Studi</h3>
            <button id="closeAddProdiModal" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('prodi.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-300 mb-1">Nama Program Studi</label>
                <input type="text" name="prodi_name" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500" value="{{ old('prodi_name') }}">
                @error('prodi_name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="button" id="cancelAddProdi" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg mr-2">
                    Batal
                </button>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal - Edit Prodi -->
<div id="editProdiModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative top-1/4 mx-auto max-w-md bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-white">Edit Program Studi</h3>
            <button id="closeEditProdiModal" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="editProdiForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_prodi_id" name="id">
            <div class="mb-4">
                <label class="block text-gray-300 mb-1">Nama Program Studi</label>
                <input type="text" id="edit_prodi_name" name="prodi_name" class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
                @error('prodi_name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="button" id="cancelEditProdi" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg mr-2">
                    Batal
                </button>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal - Delete Confirmation -->
<div id="deleteProdiModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative top-1/4 mx-auto max-w-md bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-400">Apakah Anda yakin ingin menghapus program studi ini? Tindakan ini tidak dapat dibatalkan.</p>
        </div>

        <div class="flex justify-center gap-3">
            <button id="cancelDeleteProdi" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg">
                Batal
            </button>
            <form id="deleteProdiForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" id="confirmDeleteProdi" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
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
        // Add Prodi Modal
        const openAddProdiModal = document.getElementById('openAddProdiModal');
        const addProdiModal = document.getElementById('addProdiModal');
        const closeAddProdiModal = document.getElementById('closeAddProdiModal');
        const cancelAddProdi = document.getElementById('cancelAddProdi');

        if (openAddProdiModal && addProdiModal) {
            openAddProdiModal.addEventListener('click', function() {
                addProdiModal.classList.remove('hidden');
            });
        }

        if (closeAddProdiModal) {
            closeAddProdiModal.addEventListener('click', function() {
                addProdiModal.classList.add('hidden');
            });
        }

        if (cancelAddProdi) {
            cancelAddProdi.addEventListener('click', function() {
                addProdiModal.classList.add('hidden');
            });
        }

        // Edit Prodi Modal
        const editButtons = document.querySelectorAll('.edit-prodi-btn');
        const editProdiModal = document.getElementById('editProdiModal');
        const closeEditProdiModal = document.getElementById('closeEditProdiModal');
        const cancelEditProdi = document.getElementById('cancelEditProdi');
        const editProdiForm = document.getElementById('editProdiForm');
        const editProdiId = document.getElementById('edit_prodi_id');
        const editProdiName = document.getElementById('edit_prodi_name');

        if (editButtons.length > 0 && editProdiModal && editProdiForm) {
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    if (editProdiId) editProdiId.value = id;
                    if (editProdiName) editProdiName.value = name;
                    editProdiForm.action = `{{ url('prodi') }}/${id}`;

                    editProdiModal.classList.remove('hidden');
                });
            });
        }

        if (closeEditProdiModal) {
            closeEditProdiModal.addEventListener('click', function() {
                editProdiModal.classList.add('hidden');
                editProdiForm.action = '';
            });
        }

        if (cancelEditProdi) {
            cancelEditProdi.addEventListener('click', function() {
                editProdiModal.classList.add('hidden');
                editProdiForm.action = '';
            });
        }

        // Delete Prodi Modal
        const deleteButtons = document.querySelectorAll('.delete-prodi-btn');
        const deleteProdiModal = document.getElementById('deleteProdiModal');
        const cancelDeleteProdi = document.getElementById('cancelDeleteProdi');
        const deleteProdiForm = document.getElementById('deleteProdiForm');

        if (deleteButtons.length > 0 && deleteProdiModal && deleteProdiForm) {
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const prodiId = this.getAttribute('data-id');
                    if (prodiId) {
                        deleteProdiForm.action = `{{ url('prodi') }}/${prodiId}`;
                        deleteProdiModal.classList.remove('hidden');
                    }
                });
            });
        }

        if (cancelDeleteProdi) {
            cancelDeleteProdi.addEventListener('click', function() {
                deleteProdiModal.classList.add('hidden');
                deleteProdiForm.action = '';
            });
        }
    });
</script>
@endpush
