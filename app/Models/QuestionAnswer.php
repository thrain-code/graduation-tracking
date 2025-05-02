<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_response_id',
        'question_id',
        'question_option_id',
        'text_answer'
    ];

    /**
     * Get the response that owns the answer
     */
    public function response()
    {
        return $this->belongsTo(FormResponse::class, 'form_response_id');
    }

    /**
     * Get the question that owns the answer
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the option that was selected (for multiple choice questions)
     */
    public function option()
    {
        return $this->belongsTo(QuestionOption::class, 'question_option_id');
    }
}
