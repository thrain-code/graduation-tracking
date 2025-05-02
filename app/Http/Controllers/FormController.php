<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    /**
     * Display a listing of the forms.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $forms = Form::when($search, function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.forms.index', compact('forms'));
    }

    /**
     * Show the form for creating a new form.
     */
    public function create()
    {
        $questionTypes = Question::getQuestionTypes();
        return view('admin.forms.create', compact('questionTypes'));
    }

    /**
     * Store a newly created form in storage.
     */
    public function store(Request $request)
    {
        $validator = $this->validateFormData($request);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create form
            $form = Form::create([
                'title' => $request->title,
                'description' => $request->description,
                'is_active' => $request->is_active ?? true,
                'expires_at' => $request->expires_at,
            ]);

            // Create questions and options
            foreach ($request->questions as $index => $questionData) {
                $question = $form->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'question_type' => $questionData['question_type'],
                    'order' => $index + 1,
                ]);

                // Add options for multiple choice questions
                if ($questionData['question_type'] === Question::TYPE_MULTIPLE_CHOICE && isset($questionData['options'])) {
                    foreach ($questionData['options'] as $optionIndex => $optionData) {
                        $question->options()->create([
                            'option_text' => $optionData['option_text'],
                            'order' => $optionIndex + 1,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('forms.index')
                ->with('success', 'Form berhasil dibuat. URL form: ' . $form->getFormUrl());

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified form.
     */
    public function show($id)
    {
        $form = Form::with(['questions.options'])->findOrFail($id);
        return view('admin.forms.show', compact('form'));
    }

    /**
     * Show the form for editing the specified form.
     */
    public function edit($id)
    {
        $form = Form::with(['questions.options'])->findOrFail($id);
        $questionTypes = Question::getQuestionTypes();

        return view('admin.forms.edit', compact('form', 'questionTypes'));
    }

    /**
     * Update the specified form in storage.
     */
    public function update(Request $request, $id)
    {
        $form = Form::findOrFail($id);

        $validator = $this->validateFormData($request);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Update form
            $form->update([
                'title' => $request->title,
                'description' => $request->description,
                'is_active' => $request->is_active ?? true,
                'expires_at' => $request->expires_at,
            ]);

            // Get existing question IDs
            $existingQuestionIds = $form->questions()->pluck('id')->toArray();
            $updatedQuestionIds = [];

            // Update questions and options
            foreach ($request->questions as $index => $questionData) {
                if (isset($questionData['id'])) {
                    // Update existing question
                    $question = Question::findOrFail($questionData['id']);
                    $question->update([
                        'question_text' => $questionData['question_text'],
                        'question_type' => $questionData['question_type'],
                        'order' => $index + 1,
                    ]);

                    $updatedQuestionIds[] = $question->id;

                    // Handle options for multiple choice questions
                    if ($questionData['question_type'] === Question::TYPE_MULTIPLE_CHOICE) {
                        // Get existing option IDs
                        $existingOptionIds = $question->options()->pluck('id')->toArray();
                        $updatedOptionIds = [];

                        foreach ($questionData['options'] as $optionIndex => $optionData) {
                            if (isset($optionData['id'])) {
                                // Update existing option
                                $option = QuestionOption::findOrFail($optionData['id']);
                                $option->update([
                                    'option_text' => $optionData['option_text'],
                                    'order' => $optionIndex + 1,
                                ]);

                                $updatedOptionIds[] = $option->id;
                            } else {
                                // Create new option
                                $option = $question->options()->create([
                                    'option_text' => $optionData['option_text'],
                                    'order' => $optionIndex + 1,
                                ]);

                                $updatedOptionIds[] = $option->id;
                            }
                        }

                        // Delete options that were not updated
                        $optionsToDelete = array_diff($existingOptionIds, $updatedOptionIds);
                        QuestionOption::whereIn('id', $optionsToDelete)->delete();
                    } else {
                        // Delete all options if question type is not multiple choice
                        $question->options()->delete();
                    }
                } else {
                    // Create new question
                    $question = $form->questions()->create([
                        'question_text' => $questionData['question_text'],
                        'question_type' => $questionData['question_type'],
                        'order' => $index + 1,
                    ]);

                    $updatedQuestionIds[] = $question->id;

                    // Add options for multiple choice questions
                    if ($questionData['question_type'] === Question::TYPE_MULTIPLE_CHOICE && isset($questionData['options'])) {
                        foreach ($questionData['options'] as $optionIndex => $optionData) {
                            $question->options()->create([
                                'option_text' => $optionData['option_text'],
                                'order' => $optionIndex + 1,
                            ]);
                        }
                    }
                }
            }

            // Delete questions that were not updated
            $questionsToDelete = array_diff($existingQuestionIds, $updatedQuestionIds);
            Question::whereIn('id', $questionsToDelete)->delete();

            DB::commit();

            return redirect()
                ->route('forms.index')
                ->with('success', 'Form berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified form from storage.
     */
    public function destroy($id)
    {
        $form = Form::findOrFail($id);

        try {
            $form->delete();
            return redirect()
                ->route('forms.index')
                ->with('success', 'Form berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show form results/analysis
     */
    public function results($id)
    {
        $form = Form::with(['questions.options', 'responses.answers'])->findOrFail($id);
        $totalResponses = $form->responses()->whereNotNull('completed_at')->count();

        // Prepare the results data
        $resultsData = [];

        foreach ($form->questions as $question) {
            $questionData = [
                'id' => $question->id,
                'text' => $question->question_text,
                'type' => $question->question_type,
                'total_responses' => 0
            ];

            if ($question->isMultipleChoice()) {
                $optionsData = [];

                foreach ($question->options as $option) {
                    $responseCount = $option->getResponseCount();
                    $percentage = $totalResponses > 0 ? round(($responseCount / $totalResponses) * 100, 1) : 0;

                    $optionsData[] = [
                        'id' => $option->id,
                        'text' => $option->option_text,
                        'count' => $responseCount,
                        'percentage' => $percentage
                    ];

                    $questionData['total_responses'] += $responseCount;
                }

                $questionData['options'] = $optionsData;
            } else {
                // For text questions, gather all text responses
                $textResponses = QuestionAnswer::where('question_id', $question->id)
                    ->whereNotNull('text_answer')
                    ->pluck('text_answer')
                    ->toArray();

                $questionData['text_responses'] = $textResponses;
                $questionData['total_responses'] = count($textResponses);
            }

            $resultsData[] = $questionData;
        }

        return view('admin.forms.results', compact('form', 'resultsData', 'totalResponses'));
    }

    /**
     * Generate a new form slug
     */
    public function regenerateSlug($id)
    {
        $form = Form::findOrFail($id);
        $form->slug = Form::generateUniqueSlug();
        $form->save();

        return redirect()
            ->back()
            ->with('success', 'Slug form berhasil diperbarui. URL baru: ' . $form->getFormUrl());
    }
    /**
     * Custom validation for form questions and options
     */
    private function validateFormData(Request $request)
    {
        $baseRules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|in:' . implode(',', array_keys(Question::getQuestionTypes())),
        ];

        $validator = Validator::make($request->all(), $baseRules);

        // Add dynamic validation rules for multiple choice options
        $validator->after(function ($validator) use ($request) {
            if ($request->has('questions')) {
                foreach ($request->questions as $index => $question) {
                    // Only validate options for multiple choice questions
                    if (isset($question['question_type']) && $question['question_type'] === Question::TYPE_MULTIPLE_CHOICE) {
                        // Check if options exist
                        if (!isset($question['options']) || !is_array($question['options']) || count($question['options']) < 2) {
                            $validator->errors()->add(
                                "questions.{$index}.options",
                                "Pertanyaan pilihan ganda harus memiliki minimal 2 pilihan."
                            );
                        } else {
                            // Check each option
                            foreach ($question['options'] as $optionIndex => $option) {
                                if (!isset($option['option_text']) || empty(trim($option['option_text']))) {
                                    $validator->errors()->add(
                                        "questions.{$index}.options.{$optionIndex}.option_text",
                                        "Teks pilihan tidak boleh kosong."
                                    );
                                }
                            }
                        }
                    }
                }
            }
        });

        return $validator;
    }
}
