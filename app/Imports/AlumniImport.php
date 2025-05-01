<?php

namespace App\Imports;

use App\Models\Alumni;
use App\Models\Prodi;
use App\Models\User;
use App\Models\Status;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AlumniImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures, Importable;
    
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Find or create prodi if it doesn't exist
        $prodi = Prodi::firstOrCreate(['prodi_name' => $row['prodi']]);
        
        // Create user account for the alumni
        $user = User::create([
            'name' => $row['nama_lengkap'],
            'email' => $row['email'] ?? $this->generateEmail($row['nama_lengkap']),
            'password' => Hash::make($row['nim']), // Use NIM as default password
            'is_admin' => false,
        ]);
        
        // Create alumni record
        $alumni = Alumni::create([
            'nama_lengkap' => $row['nama_lengkap'],
            'nim' => $row['nim'],
            'jenis_kelamin' => strtolower($row['jenis_kelamin']),
            'tahun_lulus' => $row['tahun_lulus'],
            'prodi_id' => $prodi->id,
            'user_id' => $user->id,
            'number_phone' => $row['number_phone'] ?? null,
            'alamat' => $row['alamat'] ?? null,
        ]);
        
        // If status data is provided, create status records
        if (!empty($row['status_kerja']) && strtolower($row['status_kerja']) === 'ya') {
            Status::create([
                'nama' => $row['nama_perusahaan'] ?? 'Tidak Disebutkan',
                'type' => 'bekerja',
                'alumni_id' => $alumni->id,
                'jabatan' => $row['jabatan'] ?? null,
                'gaji' => $this->parseGaji($row['gaji'] ?? '0'),
                'tahun_mulai' => $row['tahun_mulai_kerja'] ?? $row['tahun_lulus'],
                'is_active' => true,
            ]);
        }
        
        if (!empty($row['status_kuliah']) && strtolower($row['status_kuliah']) === 'ya') {
            Status::create([
                'nama' => $row['nama_universitas'] ?? 'Tidak Disebutkan',
                'type' => 'kuliah',
                'alumni_id' => $alumni->id,
                'jenjang' => $row['jenjang'] ?? 'S2',
                'tahun_mulai' => $row['tahun_mulai_kuliah'] ?? $row['tahun_lulus'],
                'is_active' => true,
            ]);
        }
        
        return $alumni;
    }
    
    /**
     * Generate a simple email based on name
     */
    private function generateEmail($name)
    {
        $simplifiedName = strtolower(str_replace(' ', '.', $name));
        return $simplifiedName . '@alumnus.ac.id';
    }
    
    /**
     * Parse salary input in various formats
     */
    private function parseGaji($gaji)
    {
        // Remove non-numeric characters
        $gajiNumeric = preg_replace('/[^0-9]/', '', $gaji);
        return empty($gajiNumeric) ? 0 : (int) $gajiNumeric;
    }
    
    /**
     * Define validation rules
     */
    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:alumnis,nim',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Laki-Laki,laki-laki,laki laki,Laki laki,Perempuan,perempuan',
            'tahun_lulus' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'prodi' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
        ];
    }
    
    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'nim.unique' => 'NIM sudah terdaftar dalam sistem',
            'email.unique' => 'Email sudah terdaftar dalam sistem',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
        ];
    }
}