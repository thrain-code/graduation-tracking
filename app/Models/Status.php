<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    
    // Specify table name explicitly since 'status' is singular
    protected $table = 'status';
    
    protected $fillable = [
        "nama",
        "type",
        "alumni_id",
        "jenis_pekerjaan",
        "jabatan",
        "jenjang",
        "gaji",
        "tahun_mulai",
        "is_active"
    ];
    
    protected $casts = [
        'tahun_mulai' => 'integer',
        'is_active' => 'boolean',
        'gaji' => 'integer',
    ];
    
    // Relasi dengan model Alumni
    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }
    
    // Scope untuk filter status bekerja
    public function scopeBekerja($query)
    {
        return $query->where('type', 'bekerja');
    }
    
    // Scope untuk filter status kuliah
    public function scopeKuliah($query)
    {
        return $query->where('type', 'kuliah');
    }
    
    // Scope untuk filter status aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Cek apakah status adalah status bekerja
    public function isBekerja()
    {
        return $this->type === 'bekerja';
    }
    
    // Cek apakah status adalah status kuliah
    public function isKuliah()
    {
        return $this->type === 'kuliah';
    }
}