@extends('layouts.admin')

@section('title', 'Detail Form')

@section('page-title', 'Detail Form: ' . $form->title)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('forms.index') }}" class="text-primary-400 hover:text-primary-300 flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Form
    </a>

    <div class="flex items-center space-x-3">
        <a href="{{ route('forms.edit', $form->id) }}" class="text-white bg-yellow-600 hover:bg-yellow-700 px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-edit mr-2"></i> Edit Form
        </a>
        <a href="{{ route('forms.results', $form->id) }}" class="text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-chart-bar mr-2"></i> Lihat Hasil
        </a>
        <a href="{{ route('form.show', $form->slug) }}" target="_blank" class="text-white bg-primary-600 hover:bg-primary-700 px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-external-link-alt mr-2"></i> Lihat Form
        </a>
    </div>
</div>

<!-- Form Details Card -->
<div class="card rounded-xl p-6 shadow-lg mb-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h3 class="text-xl font-semibold text-white mb-2">{{ $form->title }}</h3>
            @if($form->description)
                <p class="text-gray-300">{{ $form->description }}</p>
            @endif
        </div>

        <div>
            @if($form->isExpired())
                <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-sm">Kadaluarsa</span>
            @elseif($form->is_active)
                <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm">Aktif</span>
            @else
                <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-sm">Tidak Aktif</span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-slate-800 rounded-lg p-4">
            <h4 class="text-white font-medium mb-3">Informasi Form</h4>
            <table class="w-full text-sm">
                <tr>
                    <td class="text-gray-400 py-1">ID Form</td>
                    <td class="text-white py-1">{{ $form->id }}</td>
                </tr>
                <tr>
                    <td class="text-gray-400 py-1">Slug</td>
                    <td class="text-white py-1">{{ $form->slug }}</td>
                </tr>
                <tr>
                    <td class="text-gray-400 py-1">Status</td>
                    <td class="text-white py-1">
                        @if($form->isExpired())
                            <span class="text-red-400">Kadaluarsa</span>
                        @elseif($form->is_active)
                            <span class="text-green-400">Aktif</span>
                        @else
                            <span class="text-yellow-400">Tidak Aktif</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-gray-400 py-1">Tanggal Kadaluarsa</td>
                    <td class="text-white py-1">
                        {{ $form->expires_at ? $form->expires_at->format('d M Y H:i') : 'Tidak Ada' }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="bg-slate-800 rounded-lg p-4">
            <h4 class="text-white font-medium mb-3">Statistik</h4>
            <table class="w-full text-sm">
                <tr>
                    <td class="text-gray-400 py-1">Jumlah Pertanyaan</td>
                    <td class="text-white py-1">{{ $form->questions->count() }}</td>
                </tr>
                <tr>
                    <td class="text-gray-400 py-1">Total Responden</td>
                    <td class="text-white py-1">{{ $form->responses()->whereNotNull('completed_at')->count() }}</td>
                </tr>
                <tr>
                    <td class="text-gray-400 py-1">Dibuat Pada</td>
                    <td class="text-white py-1">{{ $form->created_at->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="text-gray-400 py-1">Terakhir Diperbarui</td>
                    <td class="text-white py-1">{{ $form->updated_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Form URL -->
    <div class="bg-slate-800 rounded-lg p-4 mb-6">
        <div class="flex justify-between items-center mb-2">
            <h4 class="text-white font-medium">URL Form</h4>
            <form action="{{ route('forms.regenerate-slug', $form->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-primary-400 hover:text-primary-300 text-sm">
                    <i class="fas fa-sync-alt mr-1"></i> Generate Baru
                </button>
            </form>
        </div>
        <div class="flex items-center">
            <input type="text" value="{{ $form->getFormUrl() }}" readonly
                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500">
            <button type="button" id="copyLinkBtn" class="ml-2 bg-primary-600 hover:bg-primary-700 text-white px-3 py-2 rounded-lg"
                data-clipboard-text="{{ $form->getFormUrl() }}">
                <i class="fas fa-copy"></i>
            </button>
        </div>
    </div>

    <!-- QR Code -->
    <div class="bg-slate-800 rounded-lg p-4">
        <h4 class="text-white font-medium mb-3">QR Code</h4>
        <div class="flex items-center">
            <div class="bg-white p-3 rounded-lg mr-4">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($form->getFormUrl()) }}"
                    alt="QR Code" class="w-32 h-32">
            </div>
            <div>
                <p class="text-gray-300 text-sm mb-2">Gunakan QR Code ini untuk membagikan form secara mudah.</p>
                <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($form->getFormUrl()) }}"
                    target="_blank" class="text-primary-400 hover:text-primary-300 text-sm">
                    <i class="fas fa-download mr-1"></i> Download QR Code
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Questions List -->
<div class="card rounded-xl p-6 shadow-lg mb-6">
    <h3 class="text-xl font-semibold text-white mb-6">Daftar Pertanyaan</h3>

    @if($form->questions->count() > 0)
        <div class="space-y-4">
            @foreach($form->questions as $index => $question)
                <div class="bg-slate-800 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="text-white font-medium">{{ $index + 1 }}. {{ $question->question_text }}</h4>
                        <span class="bg-slate-700 px-2 py-1 rounded text-xs text-gray-300">
                            {{ $question->question_type == 'multiple_choice' ? 'Pilihan Ganda' : 'Teks' }}
                        </span>
                    </div>

                    @if($question->isMultipleChoice() && $question->options->count() > 0)
                        <div class="mt-3 pl-4 border-l-2 border-slate-700">
                            <p class="text-sm text-gray-400 mb-2">Pilihan Jawaban:</p>
                            <ul class="space-y-1">
                                @foreach($question->options as $option)
                                    <li class="text-gray-300">• {{ $option->option_text }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8 border-2 border-dashed border-slate-700 rounded-lg">
            <i class="fas fa-clipboard-list text-4xl text-slate-600 mb-4"></i>
            <p class="text-slate-400">Form ini belum memiliki pertanyaan. Klik "Edit Form" untuk menambahkan pertanyaan.</p>
        </div>
    @endif
</div>

<!-- Recent Responses -->
<div class="card rounded-xl p-6 shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-white">Responden Terbaru</h3>
        <a href="{{ route('forms.results', $form->id) }}" class="text-primary-400 hover:text-primary-300">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    @php
        $recentResponses = $form->responses()->whereNotNull('completed_at')->with('alumni')->latest()->take(5)->get();
    @endphp

    @if($recentResponses->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-800 text-gray-300 text-sm">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">Responden</th>
                        <th class="px-4 py-3">Waktu Pengisian</th>
                        <th class="px-4 py-3 rounded-tr-lg text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-300">
                    @foreach($recentResponses as $response)
                        <tr class="border-b border-slate-700 hover:bg-slate-800/40">
                            <td class="px-4 py-3">
                                @if($response->alumni)
                                    {{ $response->alumni->nama_lengkap }}
                                @else
                                    <span class="text-gray-400">Anonim</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $response->completed_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <button class="text-primary-400 hover:text-primary-300 view-response-btn" data-response-id="{{ $response->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8 border-2 border-dashed border-slate-700 rounded-lg">
            <i class="fas fa-users text-4xl text-slate-600 mb-4"></i>
            <p class="text-slate-400">Belum ada responden yang mengisi form ini.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Copy form link
        const copyLinkBtn = document.getElementById('copyLinkBtn');

        if (copyLinkBtn) {
            copyLinkBtn.addEventListener('click', function() {
                const link = this.getAttribute('data-clipboard-text');
                navigator.clipboard.writeText(link).then(function() {
                    window.showAlert('Link form berhasil disalin!', 'success');
                }).catch(function() {
                    window.showAlert('Gagal menyalin link. Silakan coba lagi.', 'error');
                });
            });
        }
    });
</script>
@endpush
