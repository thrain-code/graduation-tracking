<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormResponse;
use App\Models\Question;
use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormPublicController extends Controller
{
    /**
     * Display the specified form for public/alumni access.
     */
    public function show($slug)
    {
        $form = Form::with(['questions.options'])->where('slug', $slug)->firstOrFail();

        // Check if form is available
        if (!$form->isAvailable()) {
            return view('forms.unavailable', compact('form'));
        }

        // Check if current user already completed this form
        $alreadyCompleted = false;
        $existingResponse = null;

        if (Auth::check() && Auth::user()->alumni) {
            $existingResponse = FormResponse::where('form_id', $form->id)
                ->where('alumni_id', Auth::user()->alumni->id)
                ->whereNotNull('completed_at')
                ->first();

            $alreadyCompleted = !is_null($existingResponse);
        }

        return view('forms.show', compact('form', 'alreadyCompleted', 'existingResponse'));
    }

    /**
     * Store the form response.
     */
    public function store(Request $request, $slug)
    {
        $form = Form::with(['questions'])->where('slug', $slug)->firstOrFail();

        // Check if form is available
        if (!$form->isAvailable()) {
            return redirect()->back()->with('error', 'Form tidak tersedia untuk diisi.');
        }

        // Prepare validation rules
        $rules = [];
        $messages = [];

        foreach ($form->questions as $question) {
            if ($question->isMultipleChoice()) {
                $rules["answers.{$question->id}"] = 'required';
                $messages["answers.{$question->id}.required"] = "Pertanyaan '{$question->question_text}' wajib dijawab.";
            } else {
                $rules["answers.{$question->id}"] = 'required';
                $messages["answers.{$question->id}.required"] = "Pertanyaan '{$question->question_text}' wajib dijawab.";
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create or retrieve response
            $response = null;

            if (Auth::check() && Auth::user()->alumni) {
                // Check if user already has a response for this form
                $response = FormResponse::where('form_id', $form->id)
                    ->where('alumni_id', Auth::user()->alumni->id)
                    ->first();

                if (!$response) {
                    // Create new response
                    $response = FormResponse::create([
                        'form_id' => $form->id,
                        'alumni_id' => Auth::user()->alumni->id
                    ]);
                } else {
                    // Delete existing answers
                    $response->answers()->delete();
                }
            } else {
                // Create anonymous response
                $response = FormResponse::create([
                    'form_id' => $form->id
                ]);
            }

            // Store answers
            foreach ($request->answers as $questionId => $answer) {
                $question = Question::findOrFail($questionId);

                if ($question->isMultipleChoice()) {
                    // Store multiple choice answer
                    QuestionAnswer::create([
                        'form_response_id' => $response->id,
                        'question_id' => $questionId,
                        'question_option_id' => $answer
                    ]);
                } else {
                    // Store text answer
                    QuestionAnswer::create([
                        'form_response_id' => $response->id,
                        'question_id' => $questionId,
                        'text_answer' => $answer
                    ]);
                }
            }

            // Mark response as complete
            $response->markAsComplete();

            DB::commit();

            return redirect()
                ->route('form.thankyou', ['slug' => $form->slug])
                ->with('success', 'Terima kasih telah mengisi form.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the thank you page.
     */
    public function thankYou($slug)
    {
        $form = Form::where('slug', $slug)->firstOrFail();
        return view('forms.thankyou', compact('form'));
    }

    /**
     * Display the form results for public access.
     */
    public function results($slug)
    {
        $form = Form::with(['questions.options', 'responses.answers'])->where('slug', $slug)->firstOrFail();
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

        return view('forms.results', compact('form', 'resultsData', 'totalResponses'));
    }
}
