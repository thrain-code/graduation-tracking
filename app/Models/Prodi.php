<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "prodi_name"
    ];

    // Relasi oneToMany ke model Alumni
    public function alumnis()
    {
        return $this->hasMany(Alumni::class);
    }
}