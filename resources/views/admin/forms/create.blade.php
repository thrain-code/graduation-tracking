@extends('layouts.admin')

@section('title', 'Buat Form Baru')

@section('page-title', 'Buat Form Kuesioner Baru')

@push('styles')
<style>
    .question-card {
        transition: all 0.3s ease;
    }

    .question-card:hover {
        box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.3);
    }

    .option-container {
        position: relative;
    }

    .delete-option-btn {
        opacity: 0;
        transition: opacity 0.2s;
    }

    .option-container:hover .delete-option-btn {
        opacity: 1;
    }
</style>
@endpush

@section('content')
<div class="mb-6">
    <a href="{{ route('forms.index') }}" class="text-primary-400 hover:text-primary-300 flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Form
    </a>
</div>

<form action="{{ route('forms.store') }}" method="POST" id="formBuilder">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Settings -->
        <div class="lg:col-span-1">
            <div class="card rounded-xl p-6 shadow-lg sticky top-24">
                <h3 class="text-lg font-semibold text-white mb-6">Pengaturan Form</h3>

                <!-- Form Title -->
                <div class="mb-4">
                    <label for="title" class="block text-gray-300 mb-1 font-medium">Judul Form <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" required
                        class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500 @error('title') border-red-500 @enderror"
                        value="{{ old('title') }}" placeholder="Contoh: Kuesioner Tracer Study 2025">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Description -->
                <div class="mb-4">
                    <label for="description" class="block text-gray-300 mb-1 font-medium">Deskripsi Form</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500 @error('description') border-red-500 @enderror"
                        placeholder="Informasi tentang tujuan form ini">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Status -->
                <div class="mb-4">
                    <label class="block text-gray-300 mb-1 font-medium">Status Form</label>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="rounded bg-slate-700 border-slate-600 text-primary-500 focus:ring-primary-500"
                            {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 text-gray-300">Aktif</label>
                    </div>
                    <p class="text-gray-400 text-sm mt-1">Form akan tersedia untuk diisi jika diaktifkan.</p>
                </div>

                <!-- Form Expiry -->
                <div class="mb-4">
                    <label for="expires_at" class="block text-gray-300 mb-1 font-medium">Tanggal Kadaluarsa (Opsional)</label>
                    <input type="datetime-local" id="expires_at" name="expires_at"
                        class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500"
                        value="{{ old('expires_at') }}">
                    <p class="text-gray-400 text-sm mt-1">Biarkan kosong jika form tidak memiliki batas waktu.</p>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-700">
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg">
                        Simpan Form
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Questions -->
        <div class="lg:col-span-2">
            <div class="card rounded-xl p-6 shadow-lg mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-white">Daftar Pertanyaan</h3>
                    <button type="button" id="addQuestionBtn" class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded-lg text-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Pertanyaan
                    </button>
                </div>

                <!-- Questions Container -->
                <div id="questionsContainer">
                    @if (old('questions'))
                        @foreach (old('questions') as $index => $question)
                            <div class="question-card bg-slate-800 rounded-lg p-4 mb-4" data-question-index="{{ $index }}">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="font-medium text-white">Pertanyaan #<span class="question-number">{{ $index + 1 }}</span></h4>
                                    <button type="button" class="delete-question-btn text-red-400 hover:text-red-300">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-300 mb-1 text-sm">Teks Pertanyaan <span class="text-red-500">*</span></label>
                                    <input type="text" name="questions[{{ $index }}][question_text]" required
                                        class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-500"
                                        value="{{ $question['question_text'] ?? '' }}" placeholder="Contoh: Bagaimana penilaian Anda terhadap kurikulum?">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-300 mb-1 text-sm">Tipe Pertanyaan <span class="text-red-500">*</span></label>
                                    <select name="questions[{{ $index }}][question_type]" required
                                        class="question-type-select w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-500">
                                        @foreach ($questionTypes as $value => $label)
                                            <option value="{{ $value }}" {{ ($question['question_type'] ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Options for Multiple Choice -->
                                <div class="options-container {{ ($question['question_type'] ?? '') != 'multiple_choice' ? 'hidden' : '' }}">
                                    <div class="mb-2 flex justify-between items-center">
                                        <label class="block text-gray-300 text-sm">Pilihan Jawaban <span class="text-red-500">*</span></label>
                                        <button type="button" class="add-option-btn text-primary-400 hover:text-primary-300 text-sm">
                                            <i class="fas fa-plus mr-1"></i> Tambah Pilihan
                                        </button>
                                    </div>

                                    <div class="options-list">
                                        @if (isset($question['options']) && is_array($question['options']))
                                            @foreach ($question['options'] as $optionIndex => $option)
                                                <div class="option-container flex items-center mb-2">
                                                    <input type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}][option_text]"
                                                        class="flex-grow bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-500"
                                                        value="{{ $option['option_text'] ?? '' }}" placeholder="Teks pilihan">
                                                    <button type="button" class="delete-option-btn ml-2 text-red-400 hover:text-red-300">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="option-container flex items-center mb-2">
                                                <input type="text" name="questions[{{ $index }}][options][0][option_text]"
                                                    class="flex-grow bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-500"
                                                    placeholder="Teks pilihan">
                                                <button type="button" class="delete-option-btn ml-2 text-red-400 hover:text-red-300">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <div class="option-container flex items-center mb-2">
                                                <input type="text" name="questions[{{ $index }}][options][1][option_text]" 
                                                    class="flex-grow bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-500"
                                                    placeholder="Teks pilihan">
                                                <button type="button" class="delete-option-btn ml-2 text-red-400 hover:text-red-300">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- No Questions Message -->
                <div id="noQuestionsMessage" class="{{ old('questions') ? 'hidden' : '' }} text-center py-10 border-2 border-dashed border-slate-700 rounded-lg">
                    <i class="fas fa-clipboard-list text-4xl text-slate-600 mb-4"></i>
                    <p class="text-slate-400">Belum ada pertanyaan. Klik "Tambah Pertanyaan" untuk mulai membuat form.</p>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Question Template (Hidden) -->
<template id="questionTemplate">
    <div class="question-card bg-slate-800 rounded-lg p-4 mb-4" data-question-index="__INDEX__">
        <div class="flex justify-between items-start mb-4">
            <h4 class="font-medium text-white">Pertanyaan #<span class="question-number">__NUMBER__</span></h4>
            <button type="button" class="delete-question-btn text-red-400 hover:text-red-300">
                <i class="fas fa-trash"></i>
            </button>
        </div>

        <div class="mb-4">
            <label class="block text-gray-300 mb-1 text-sm">Teks Pertanyaan <span class="text-red-500">*</span></label>
            <input type="text" name="questions[__INDEX__][question_text]" required
                class="w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-500"
                placeholder="Contoh: Bagaimana penilaian Anda terhadap kurikulum?">
        </div>

        <div class="mb-4">
            <label class="block text-gray-300 mb-1 text-sm">Tipe Pertanyaan <span class="text-red-500">*</span></label>
            <select name="questions[__INDEX__][question_type]" required
                class="question-type-select w-full bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-500">
                @foreach ($questionTypes as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <!-- Options for Multiple Choice -->
        <div class="options-container {{ array_key_first($questionTypes) != 'multiple_choice' ? 'hidden' : '' }}">
            <div class="mb-2 flex justify-between items-center">
                <label class="block text-gray-300 text-sm">Pilihan Jawaban <span class="text-red-500">*</span></label>
                <button type="button" class="add-option-btn text-primary-400 hover:text-primary-300 text-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Pilihan
                </button>
            </div>

            <div class="options-list">
                <div class="option-container flex items-center mb-2">
                    <input type="text" name="questions[__INDEX__][options][0][option_text]"
                        class="flex-grow bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-500"
                        placeholder="Teks pilihan">
                    <button type="button" class="delete-option-btn ml-2 text-red-400 hover:text-red-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="option-container flex items-center mb-2">
                    <input type="text" name="questions[__INDEX__][options][1][option_text]"
                        class="flex-grow bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-500"
                        placeholder="Teks pilihan">
                    <button type="button" class="delete-option-btn ml-2 text-red-400 hover:text-red-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Option Template (Hidden) -->
<template id="optionTemplate">
    <div class="option-container flex items-center mb-2">
        <input type="text" name="questions[__QUESTION_INDEX__][options][__OPTION_INDEX__][option_text]"
            class="flex-grow bg-slate-700 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-primary-500"
            placeholder="Teks pilihan">
        <button type="button" class="delete-option-btn ml-2 text-red-400 hover:text-red-300">
            <i class="fas fa-times"></i>
        </button>
    </div>
</template>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const questionsContainer = document.getElementById('questionsContainer');
        const noQuestionsMessage = document.getElementById('noQuestionsMessage');
        const addQuestionBtn = document.getElementById('addQuestionBtn');
        const questionTemplate = document.getElementById('questionTemplate').innerHTML;
        const optionTemplate = document.getElementById('optionTemplate').innerHTML;
        const formBuilder = document.getElementById('formBuilder');

        // Initialize question counter
        let questionCounter = questionsContainer.querySelectorAll('.question-card').length || 0;

        // Add new question
        addQuestionBtn.addEventListener('click', function() {
            // Hide "no questions" message
            noQuestionsMessage.classList.add('hidden');

            // Create new question
            const newQuestion = questionTemplate
                .replace(/__INDEX__/g, questionCounter)
                .replace(/__NUMBER__/g, questionCounter + 1);

            // Append to container
            questionsContainer.insertAdjacentHTML('beforeend', newQuestion);

            // Set up event listeners for new question
            setupQuestionEventListeners(questionsContainer.lastElementChild);

            // Increment counter
            questionCounter++;
        });

        // Setup initial questions
        document.querySelectorAll('.question-card').forEach(question => {
            setupQuestionEventListeners(question);
        });

        // Setup event listeners for a question
        function setupQuestionEventListeners(questionElement) {
            // Question type change
            const typeSelect = questionElement.querySelector('.question-type-select');
            const optionsContainer = questionElement.querySelector('.options-container');

            typeSelect.addEventListener('change', function() {
                if (this.value === 'multiple_choice') {
                    optionsContainer.classList.remove('hidden');
                } else {
                    optionsContainer.classList.add('hidden');
                }
            });

            // Delete question
            const deleteBtn = questionElement.querySelector('.delete-question-btn');
            deleteBtn.addEventListener('click', function() {
                questionElement.remove();

                // Update question numbers
                updateQuestionNumbers();

                // Show "no questions" message if no questions left
                if (questionsContainer.children.length === 0) {
                    noQuestionsMessage.classList.remove('hidden');
                }
            });

            // Add option
            const addOptionBtn = questionElement.querySelector('.add-option-btn');
            addOptionBtn.addEventListener('click', function() {
                const optionsList = questionElement.querySelector('.options-list');
                const questionIndex = questionElement.getAttribute('data-question-index');
                const optionIndex = optionsList.children.length;

                const newOption = optionTemplate
                    .replace(/__QUESTION_INDEX__/g, questionIndex)
                    .replace(/__OPTION_INDEX__/g, optionIndex);

                optionsList.insertAdjacentHTML('beforeend', newOption);

                // Setup delete option event
                setupDeleteOption(optionsList.lastElementChild);
            });

            // Setup delete option events for existing options
            questionElement.querySelectorAll('.option-container').forEach(option => {
                setupDeleteOption(option);
            });
        }

        // Setup delete option button
        function setupDeleteOption(optionElement) {
            const deleteBtn = optionElement.querySelector('.delete-option-btn');
            deleteBtn.addEventListener('click', function() {
                const optionsList = optionElement.parentElement;

                // Prevent deleting if only two options left
                if (optionsList.children.length <= 2) {
                    window.showAlert('Pertanyaan pilihan ganda harus memiliki minimal 2 pilihan.', 'error');
                    return;
                }

                optionElement.remove();

                // Update option indices
                updateOptionIndices(optionsList);
            });
        }

        // Update question numbers
        function updateQuestionNumbers() {
            document.querySelectorAll('.question-card').forEach((question, index) => {
                question.querySelector('.question-number').textContent = index + 1;

                // Update question index attribute
                question.setAttribute('data-question-index', index);

                // Update question name attributes
                question.querySelector('input[name^="questions["][name$="][question_text]"]').name = `questions[${index}][question_text]`;
                question.querySelector('select[name^="questions["][name$="][question_type]"]').name = `questions[${index}][question_type]`;

                // Update option name attributes
                const optionsList = question.querySelector('.options-list');
                if (optionsList) {
                    updateOptionIndices(optionsList, index);
                }
            });
        }

        // Update option indices
        function updateOptionIndices(optionsList, questionIndex = null) {
            const options = optionsList.querySelectorAll('.option-container');

            options.forEach((option, optionIndex) => {
                const input = option.querySelector('input');

                if (questionIndex !== null) {
                    // If question index was provided, update both question and option indices
                    input.name = `questions[${questionIndex}][options][${optionIndex}][option_text]`;
                } else {
                    // Otherwise, only update option index
                    const nameParts = input.name.split('[options]');
                    input.name = `${nameParts[0]}[options][${optionIndex}][option_text]`;
                }
            });
        }

        // Form submission validation
        formBuilder.addEventListener('submit', function(e) {
            // Prevent the default form submission
            e.preventDefault();

            // Check if there are any questions
            if (questionsContainer.children.length === 0) {
                window.showAlert('Form harus memiliki minimal 1 pertanyaan.', 'error');
                return;
            }

            // Mark validation as initially valid
            let isValid = true;

            // For each question, handle validation based on type
            document.querySelectorAll('.question-card').forEach(question => {
                const typeSelect = question.querySelector('.question-type-select');
                const questionText = question.querySelector('input[name$="[question_text]"]');

                // Validate question text
                if (!questionText.value.trim()) {
                    isValid = false;
                    const questionNumber = question.querySelector('.question-number').textContent;
                    window.showAlert(`Pertanyaan #${questionNumber} belum diisi.`, 'error');
                }

                // If question type is multiple choice, check options
                if (typeSelect.value === 'multiple_choice') {
                    const optionsList = question.querySelector('.options-list');
                    const options = optionsList.querySelectorAll('.option-container');

                    // Check if there are at least 2 options
                    if (options.length < 2) {
                        isValid = false;
                        const questionNumber = question.querySelector('.question-number').textContent;
                        window.showAlert(`Pertanyaan #${questionNumber} (pilihan ganda) harus memiliki minimal 2 pilihan.`, 'error');
                    }

                    // Check each option for empty value
                    options.forEach((option, index) => {
                        const optionInput = option.querySelector('input[name$="[option_text]"]');
                        if (!optionInput.value.trim()) {
                            isValid = false;
                            const questionNumber = question.querySelector('.question-number').textContent;
                            window.showAlert(`Pilihan #${index + 1} pada Pertanyaan #${questionNumber} belum diisi.`, 'error');
                        }
                    });
                }
            });

            // If all validation passes, manually submit the form
            if (isValid) {
                // Important: For multiple choice questions that are hidden, remove the required attribute
                if (document.querySelectorAll('.options-container.hidden').length > 0) {
                    document.querySelectorAll('.options-container.hidden input[required]').forEach(input => {
                        input.removeAttribute('required');
                    });
                }

                // Submit the form
                this.submit();
            }
        });

        // Add first question if none exist
        if (questionCounter === 0) {
            addQuestionBtn.click();
        }
    });
</script>
@endpush
