<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'alumni_id',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Get the form that owns the response
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the alumni that owns the response
     */
    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

    /**
     * Get the answers for the response
     */
    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class);
    }

    /**
     * Check if the response is complete
     */
    public function isComplete()
    {
        return !is_null($this->completed_at);
    }

    /**
     * Mark the response as complete
     */
    public function markAsComplete()
    {
        $this->completed_at = now();
        $this->save();

        return $this;
    }
}
