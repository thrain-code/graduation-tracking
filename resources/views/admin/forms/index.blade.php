@extends('layouts.admin')

@section('title', 'Kelola Form')

@section('page-title', 'Kelola Form Kuesioner')

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

<!-- Forms Card -->
<div class="card rounded-xl p-6 shadow-lg mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h3 class="text-xl font-semibold text-white">Daftar Form Kuesioner</h3>

        <a href="{{ route('forms.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-plus mr-2"></i> Buat Form Baru
        </a>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <form method="GET" action="{{ route('forms.index') }}">
            <div class="relative">
                <input type="text" name="search" placeholder="Cari form..." value="{{ request('search') }}" class="w-full bg-slate-700 border border-slate-600 rounded-lg pl-10 pr-4 py-2 text-white focus:outline-none focus:border-primary-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </form>
    </div>

    <!-- Forms Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-800 text-gray-300 text-sm">
                <tr>
                    <th class="px-4 py-3 rounded-tl-lg">#</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Responden</th>
                    <th class="px-4 py-3">Tanggal Dibuat</th>
                    <th class="px-4 py-3 rounded-tr-lg text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-300">
                @forelse ($forms as $form)
                <tr class="border-b border-slate-700 hover:bg-slate-800/40">
                    <td class="px-4 py-3">{{ $forms->firstItem() + $loop->index }}</td>
                    <td class="px-4 py-3">{{ $form->title }}</td>
                    <td class="px-4 py-3">
                        <span class="bg-slate-700 px-2 py-1 rounded text-xs">{{ $form->slug }}</span>
                        <a href="{{ $form->getFormUrl() }}" target="_blank" class="ml-2 text-primary-400 text-xs hover:text-primary-300">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </td>
                    <td class="px-4 py-3">
                        @if ($form->isExpired())
                            <span class="bg-red-500/20 text-red-400 px-2 py-1 rounded-full text-xs">Kadaluarsa</span>
                        @elseif ($form->is_active)
                            <span class="bg-green-500/20 text-green-400 px-2 py-1 rounded-full text-xs">Aktif</span>
                        @else
                            <span class="bg-yellow-500/20 text-yellow-400 px-2 py-1 rounded-full text-xs">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        {{ $form->responses()->whereNotNull('completed_at')->count() }}
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $form->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('forms.show', $form->id) }}" class="text-blue-400 hover:text-blue-300" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('forms.results', $form->id) }}" class="text-green-400 hover:text-green-300" title="Lihat Hasil">
                                <i class="fas fa-chart-bar"></i>
                            </a>
                            <a href="{{ route('forms.edit', $form->id) }}" class="text-yellow-400 hover:text-yellow-300" title="Edit Form">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="text-red-400 hover:text-red-300 delete-form-btn"
                                    data-id="{{ $form->id }}"
                                    data-title="{{ $form->title }}"
                                    title="Hapus Form">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-400">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-clipboard-list text-4xl mb-4 opacity-50"></i>
                            <p class="mb-2">Belum ada form kuesioner yang dibuat.</p>
                            <a href="{{ route('forms.create') }}" class="text-primary-400 hover:text-primary-300">
                                Buat form baru <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
        @if ($forms->count() > 0)
            <p class="text-sm text-gray-400">Menampilkan {{ $forms->count() }} dari {{ $forms->total() }} form</p>
            <div class="flex">
                {{ $forms->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('modals')
<!-- Delete Confirmation Modal -->
<div id="deleteFormModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative top-1/4 mx-auto max-w-md bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-700">
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-400">Apakah Anda yakin ingin menghapus form "<span id="deleteFormTitle" class="font-semibold"></span>"? Semua data termasuk pertanyaan dan tanggapan akan dihapus permanen.</p>
        </div>

        <div class="flex justify-center gap-3">
            <button id="cancelDeleteForm" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg">
                Batal
            </button>
            <form id="deleteFormForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
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
        // Delete Form Modal
        const deleteButtons = document.querySelectorAll('.delete-form-btn');
        const deleteFormModal = document.getElementById('deleteFormModal');
        const deleteFormTitle = document.getElementById('deleteFormTitle');
        const cancelDeleteForm = document.getElementById('cancelDeleteForm');
        const deleteFormForm = document.getElementById('deleteFormForm');

        if (deleteButtons.length > 0 && deleteFormModal && deleteFormForm) {
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const formId = this.getAttribute('data-id');
                    const formTitle = this.getAttribute('data-title');

                    if (formId && deleteFormTitle) {
                        deleteFormTitle.textContent = formTitle;
                        deleteFormForm.action = `{{ url('admin/forms') }}/${formId}`;
                        deleteFormModal.classList.remove('hidden');
                    }
                });
            });
        }

        if (cancelDeleteForm) {
            cancelDeleteForm.addEventListener('click', function() {
                deleteFormModal.classList.add('hidden');
                deleteFormForm.action = '';
            });
        }
    });
</script>
@endpush
