<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;
    protected $fillable = [
        "nama_lengkap",
        "nim",
        "jenis_kelamin",
        "tahun_lulus",
        "prodi_id",
        "user_id",
        "number_phone",
        "alamat"
    ];

    // Relasi oneToMany dari model Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    // Relasi oneToOne ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi oneToMany ke model Pekerjaan
    public function pekerjaans()
    {
        return $this->hasMany(Pekerjaan::class);
    }

    // Relasi oneToMany ke model PendidikanLanjutan
    public function pendidikanLanjutans()
    {
        return $this->hasMany(PendidikanLanjutan::class);
    }
}
