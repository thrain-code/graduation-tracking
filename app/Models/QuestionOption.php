<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'option_text',
        'order'
    ];

    /**
     * Get the question that owns the option
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the answers that selected this option
     */
    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class);
    }

    /**
     * Get the count of responses that selected this option
     */
    public function getResponseCount()
    {
        return $this->answers()->count();
    }
}
