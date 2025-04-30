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
    
    protected $casts = [
        'tahun_lulus' => 'integer',
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
    
    // Relasi oneToMany ke model Status
    public function status()
    {
        return $this->hasMany(Status::class, 'alumni_id');
    }
    
    // Alias untuk metode status() untuk konsistensi
    public function statuses()
    {
        return $this->status();
    }
    
    // Mendapatkan status aktif
    public function activeStatuses()
    {
        return $this->status()->where('is_active', true)->get();
    }
    
    // Mendapatkan status aktif berdasarkan tipe
    public function getActiveStatusByType($type)
    {
        return $this->status()
                    ->where('type', $type)
                    ->where('is_active', true)
                    ->first();
    }
    
    // Mendapatkan semua status bekerja
    public function statusBekerja()
    {
        return $this->status()->where('type', 'bekerja')->get();
    }
    
    // Mendapatkan semua status kuliah
    public function statusKuliah()
    {
        return $this->status()->where('type', 'kuliah')->get();
    }
    
    // Cek apakah alumni sedang bekerja
    public function isBekerja()
    {
        return $this->status()
                    ->where('type', 'bekerja')
                    ->where('is_active', true)
                    ->exists();
    }
    
    // Cek apakah alumni sedang kuliah
    public function isKuliah()
    {
        return $this->status()
                    ->where('type', 'kuliah')
                    ->where('is_active', true)
                    ->exists();
    }
}