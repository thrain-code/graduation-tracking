<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    protected $fillable = [
        "nama_lengkap",
        "nim",
        "jenis_kelamin",
        "tahun_lulus",
        "prodi_id",
        "number_phone",
        "alamat"
    ];

    // Relasi oneToMany dari model Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
