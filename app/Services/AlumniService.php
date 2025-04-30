<?php
namespace App\Services;
use App\Models\Alumni;
use App\Models\Pekerjaan;
use App\Models\PendidikanLanjutan;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AlumniService
{
    // Mengambil semua data alumni
    public function getAll()
    {
        return Alumni::all();
    }
    
    // Menambahkan data alumni
    public function add(array $data): bool
    {
        try {
            $alumni = Alumni::create($data);
            return $alumni ? true : false;
        } catch (Exception $e) {
            Log::error('Error adding alumni: ' . $e->getMessage());
            return false;
        }
    }
    
    // Menampilkan data alumni berdasarkan ID
    public function showById(int $id)
    {
        return Alumni::findOrFail($id);
    }
    
    // Update data alumni
    public function update(int $id, array $data): bool
    {
        try {
            $alumni = Alumni::findOrFail($id);
            return $alumni->update($data);
        } catch (Exception $e) {
            Log::error('Error updating alumni: ' . $e->getMessage());
            return false;
        }
    }
    
    // Menghapus data alumni
    public function delete(int $id): bool
    {
        try {
            $alumni = Alumni::findOrFail($id);
            return $alumni->delete();
        } catch (Exception $e) {
            Log::error('Error deleting alumni: ' . $e->getMessage());
            return false;
        }
    }
    
    // Membuat Alumni dengan usernya
    public function createWithUser(array $dataAlumni, array $dataUser)
    {
        try {
            $alumni = Alumni::create($dataAlumni);
            
            $dataUser['password'] = Hash::make($dataUser['password']);
            $dataUser['is_admin'] = false;
            
            $user = User::create($dataUser);
            
            // Update alumni with user_id
            $alumni->user_id = $user->id;
            $alumni->save();
            
            return true;
        } catch(Exception $e){
            Log::error('Error creating alumni with user: ' . $e->getMessage());
            return false;
        }
    }
    
    // Menampilkan riwayat pekerjaan berdasarkan ID alumni
    public function getPekerjaanByAlumniId(int $alumniId)
    {
        return Pekerjaan::where('alumni_id', $alumniId)->get();
    }
    
    // Menampilkan riwayat pendidikan lanjutan berdasarkan ID alumni
    public function getPendidikanByAlumniId(int $alumniId)
    {
        return PendidikanLanjutan::where('alumni_id', $alumniId)->get();
    }
    
    // Menampilkan user yang terhubung dengan alumni
    public function getUserByAlumniId(int $alumniId)
    {
        $alumni = Alumni::findOrFail($alumniId);
        return $alumni->user;
    }
    
    // Menampilkan data alumni lengkap beserta prodi, pekerjaan, dan pendidikan lanjutan
    public function getDetailAlumni(int $alumniId)
    {
        return Alumni::with(['prodi', 'pekerjaans', 'pendidikanLanjutans', 'user'])
                      ->findOrFail($alumniId);
    }
    
    // Filter alumni berdasarkan tahun lulus
    public function getByTahunLulus(int $tahun)
    {
        return Alumni::where('tahun_lulus', $tahun)->get();
    }
    
    // Filter alumni berdasarkan prodi
    public function getByProdi(int $prodiId)
    {
        return Alumni::where('prodi_id', $prodiId)->get();
    }

    // menampilkan alumni dengan prodi
    public function getAlumniWithProdi()
    {
        return Alumni::with('prodi')->get();
    }
}