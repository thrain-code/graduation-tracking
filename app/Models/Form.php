<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'is_active',
        'expires_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Generate a unique random slug
     *
     * @return string
     */
    public static function generateUniqueSlug()
    {
        $slug = Str::random(8);

        // Check if slug already exists
        while (self::where('slug', $slug)->exists()) {
            $slug = Str::random(8);
        }

        return $slug;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug before creating a new form
        static::creating(function ($form) {
            if (empty($form->slug)) {
                $form->slug = self::generateUniqueSlug();
            }
        });
    }

    /**
     * Get the questions for the form
     */
    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * Get the responses for the form
     */
    public function responses()
    {
        return $this->hasMany(FormResponse::class);
    }

    /**
     * Get the URL for the form
     */
    public function getFormUrl()
    {
        return url("/form/{$this->slug}");
    }

    /**
     * Get the URL for the form results
     */
    public function getResultsUrl()
    {
        return url("/form/{$this->slug}/result");
    }

    /**
     * Check if the form is expired
     */
    public function isExpired()
    {
        return $this->expires_at && now()->greaterThan($this->expires_at);
    }

    /**
     * Check if the form is available
     */
    public function isAvailable()
    {
        return $this->is_active && !$this->isExpired();
    }
}
