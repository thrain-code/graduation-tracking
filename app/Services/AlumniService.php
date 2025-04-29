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
            return false;
        }
    }

    // Membuat Alumni dengan usernya
    public function createWithUser(array $dataAlumni, array $dataUser)
    {
        try {
            $alumni = Alumni::create($dataAlumni);

            $dataUser['alumni_id'] = $alumni->id;
            $dataUser['password'] = Hash::make($dataUser['password']);
            $dataUser['is_admin'] = false;

            User::create($dataUser);
            return true;
        } catch(Exception $e){
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
         return User::where('alumni_id', $alumniId)->first();
     }
 
     // Menampilkan data alumni lengkap beserta prodi, pekerjaan, dan pendidikan lanjutan
     public function getDetailAlumni(int $alumniId)
     {
         return Alumni::with(['prodi', 'pekerjaan', 'pendidikanLanjutan', 'user'])
                      ->findOrFail($alumniId);
     }
}
