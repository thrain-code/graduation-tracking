<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $form->title }} - Institut Prima Bangsa</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                    },
                },
            },
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #f8fafc;
            min-height: 100vh;
        }

        .form-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        .question-card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        .question-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }

        /* Custom radio and checkbox */
        .custom-radio, .custom-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            background: rgba(15, 23, 42, 0.5);
            border: 2px solid rgba(148, 163, 184, 0.5);
            border-radius: 50%;
            outline: none;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }

        .custom-radio:checked, .custom-checkbox:checked {
            background: rgba(14, 165, 233, 0.8);
            border-color: rgba(14, 165, 233, 1);
        }

        .custom-radio:checked:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }

        .custom-checkbox {
            border-radius: 4px;
        }

        .custom-checkbox:checked:after {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
        }

        .option-label {
            transition: all 0.2s;
        }

        .option-label:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Alert popup styles */
        .alert-popup {
            backdrop-filter: blur(8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.3s ease-out forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <!-- Alerts Container -->
    <div id="alertsContainer" class="fixed top-5 right-5 z-50 flex flex-col gap-3 max-w-md w-full"></div>

    <!-- Alert Templates (hidden) -->
    <template id="alertTemplate">
        <div class="alert-popup bg-opacity-90 shadow-lg rounded-lg p-4 flex items-center">
            <i class="alert-icon mr-3 text-lg"></i>
            <div class="flex-1 pr-3">
                <p class="alert-message font-medium"></p>
            </div>
            <button class="close-alert text-slate-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </template>

    <!-- Header -->
    <header class="bg-slate-900 bg-opacity-90 backdrop-blur-lg p-4 shadow-md sticky top-0 z-10">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('assets/logo.ico') }}" alt="Institut Prima Bangsa Logo" class="h-8 w-auto mr-3">
                <span class="text-white font-bold text-xl">Institut Prima Bangsa</span>
            </div>

            <div>
                <a href="{{ route('form.results', $form->slug) }}" class="text-primary-400 hover:text-primary-300">
                    <i class="fas fa-chart-bar mr-1"></i> Lihat Hasil
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow p-4">
        <div class="max-w-4xl mx-auto">
            @if(session('success'))
                <div class="bg-green-900 bg-opacity-90 text-green-400 p-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-900 bg-opacity-90 text-red-400 p-4 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form Header -->
            <div class="form-card rounded-xl p-6 shadow-lg mb-6">
                <h1 class="text-2xl font-bold text-white mb-2">{{ $form->title }}</h1>

                @if($form->description)
                    <p class="text-gray-300 mb-4">{{ $form->description }}</p>
                @endif

                <div class="flex items-center text-sm text-gray-400">
                    <span class="flex items-center mr-4">
                        <i class="fas fa-clipboard-list mr-2"></i> {{ count($form->questions) }} Pertanyaan
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-users mr-2"></i> {{ $form->responses()->whereNotNull('completed_at')->count() }} Responden
                    </span>
                </div>
            </div>

            @if($alreadyCompleted)
                <!-- Already Completed Message -->
                <div class="form-card rounded-xl p-6 shadow-lg text-center">
                    <div class="py-10">
                        <div class="w-20 h-20 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-4xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-white mb-2">Anda Sudah Mengisi Form Ini</h2>
                        <p class="text-gray-300 max-w-xl mx-auto mb-6">
                            Terima kasih telah berpartisipasi. Anda dapat melihat hasil dari form ini dengan mengklik tombol di bawah.
                        </p>
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('form.results', $form->slug) }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg">
                                <i class="fas fa-chart-bar mr-2"></i> Lihat Hasil
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Form Questions -->
                <form action="{{ route('form.store', $form->slug) }}" method="POST" id="responseForm">
                    @csrf

                    @foreach($form->questions as $index => $question)
                        <div class="question-card rounded-xl p-6 shadow-lg mb-6">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-white">{{ $index + 1 }}. {{ $question->question_text }}</h3>
                            </div>

                            @if($question->isMultipleChoice())
                                <div class="space-y-3">
                                    @foreach($question->options as $option)
                                        <label class="flex items-start option-label p-2 rounded-lg cursor-pointer">
                                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                                class="custom-radio mt-1" {{ old("answers.{$question->id}") == $option->id ? 'checked' : '' }}>
                                            <span class="ml-2 text-gray-300">{{ $option->option_text }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                @error("answers.{$question->id}")
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            @else
                                <div>
                                    <textarea name="answers[{{ $question->id }}]" rows="3"
                                        class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-500 @error("answers.{$question->id}") border-red-500 @enderror"
                                        placeholder="Tulis jawaban Anda di sini...">{{ old("answers.{$question->id}") }}</textarea>

                                    @error("answers.{$question->id}")
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <div class="flex justify-end mb-10">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium px-6 py-3 rounded-lg shadow-lg transition transform hover:scale-105">
                            Kirim Jawaban <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 py-6 mt-auto">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <div class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Institut Prima Bangsa - Sistem Pelacakan Alumni
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Alert system
            const alertTypes = {
                success: {
                    bgClass: 'bg-green-900',
                    iconClass: 'fas fa-check-circle text-green-400'
                },
                error: {
                    bgClass: 'bg-red-900',
                    iconClass: 'fas fa-exclamation-circle text-red-400'
                },
                warning: {
                    bgClass: 'bg-yellow-900',
                    iconClass: 'fas fa-exclamation-triangle text-yellow-400'
                },
                info: {
                    bgClass: 'bg-blue-900',
                    iconClass: 'fas fa-info-circle text-blue-400'
                }
            };

            const alertsContainer = document.getElementById('alertsContainer');
            const alertTemplate = document.getElementById('alertTemplate');

            // Function to show an alert
            function showAlert(message, type = 'info', duration = 5000) {
                // Create alert from template
                const alert = alertTemplate.content.cloneNode(true).querySelector('.alert-popup');

                // Set alert type styling
                const alertConfig = alertTypes[type] || alertTypes.info;
                alert.classList.add(alertConfig.bgClass);
                alert.querySelector('.alert-icon').className = `alert-icon mr-3 text-lg ${alertConfig.iconClass}`;

                // Set message
                alert.querySelector('.alert-message').textContent = message;

                // Add to container
                alertsContainer.appendChild(alert);

                // Set up auto-dismiss
                const dismissTimeout = setTimeout(() => {
                    dismissAlert(alert);
                }, duration);

                // Store the timeout ID with the alert
                alert.dataset.timeoutId = dismissTimeout;

                // Set up manual close button
                alert.querySelector('.close-alert').addEventListener('click', function() {
                    clearTimeout(parseInt(alert.dataset.timeoutId));
                    dismissAlert(alert);
                });

                return alert;
            }

            // Function to dismiss an alert
            function dismissAlert(alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';

                setTimeout(() => {
                    if (alert.parentNode === alertsContainer) {
                        alertsContainer.removeChild(alert);
                    }
                }, 300);
            }

            // Make the alert function available globally
            window.showAlert = showAlert;

            // Form submission
            const responseForm = document.getElementById('responseForm');

            if (responseForm) {
                responseForm.addEventListener('submit', function(e) {
                    // Basic client-side validation
                    const requiredInputs = this.querySelectorAll('input[type="radio"], textarea');
                    let isValid = true;

                    // Group inputs by name
                    const inputGroups = {};

                    requiredInputs.forEach(input => {
                        const name = input.name;

                        if (!inputGroups[name]) {
                            inputGroups[name] = [];
                        }

                        inputGroups[name].push(input);
                    });

                    // Check each group
                    Object.entries(inputGroups).forEach(([name, inputs]) => {
                        if (inputs[0].type === 'radio') {
                            // For radio buttons, check if any in the group is checked
                            const isChecked = inputs.some(input => input.checked);

                            if (!isChecked) {
                                isValid = false;
                                // Get question number
                                const questionCard = inputs[0].closest('.question-card');
                                const questionNumber = questionCard.querySelector('h3').textContent.split('.')[0];

                                showAlert(`Pertanyaan #${questionNumber} belum dijawab.`, 'error');
                            }
                        } else if (inputs[0].tagName === 'TEXTAREA') {
                            // For textareas, check if they have content
                            if (!inputs[0].value.trim()) {
                                isValid = false;
                                // Get question number
                                const questionCard = inputs[0].closest('.question-card');
                                const questionNumber = questionCard.querySelector('h3').textContent.split('.')[0];

                                showAlert(`Pertanyaan #${questionNumber} belum dijawab.`, 'error');
                            }
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        showAlert('Harap jawab semua pertanyaan sebelum mengirim.', 'error');
                    }
                });
            }
        });
    </script>
</body>
</html>
