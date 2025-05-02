<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'question_text',
        'question_type',
        'order'
    ];

    /**
     * The possible question types
     */
    const TYPE_MULTIPLE_CHOICE = 'multiple_choice';
    const TYPE_TEXT = 'text';

    /**
     * Get all available question types
     */
    public static function getQuestionTypes()
    {
        return [
            self::TYPE_MULTIPLE_CHOICE => 'Pilihan Ganda',
            self::TYPE_TEXT => 'Teks'
        ];
    }

    /**
     * Get the form that owns the question
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the options for the question
     */
    public function options()
    {
        return $this->hasMany(QuestionOption::class)->orderBy('order');
    }

    /**
     * Get the answers for the question
     */
    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class);
    }

    /**
     * Check if the question is multiple choice
     */
    public function isMultipleChoice()
    {
        return $this->question_type === self::TYPE_MULTIPLE_CHOICE;
    }

    /**
     * Check if the question is text
     */
    public function isText()
    {
        return $this->question_type === self::TYPE_TEXT;
    }
}
