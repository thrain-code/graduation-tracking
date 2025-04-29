<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendidikanLanjutan extends Model
{
    protected $fillable = [
        'alumni_id',
        'jenjang',
        'institusi',
        'jurusan',
        'tahun_mulai',
        'tahun_selesai',
    ];

    // Relasi oneToMany ke model Alumni
    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

}
