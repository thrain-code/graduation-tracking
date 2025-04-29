<?php

namespace App\Services;

use App\Models\Prodi;
use Exception;
use Illuminate\Support\Facades\Log;

class ProdiService 
{
    // Mengambil semua data prodi
    public function getAll()
    {
        return Prodi::all();
    }

    // Menambahkan data prodi
    public function add(array $data): bool
    {
        try {
            $prodi = Prodi::create($data);
            return $prodi ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    // Menampilkan Prodi berdasarkan id
    public function showById(int $id)
    {
        return Prodi::findOrFail($id);
    }

    // Update Prodi
    public function update(int $id, array $data): bool
    {
        try {
            $prodi = Prodi::findOrFail($id);
            return $prodi->update($data);
        } catch (Exception $e) {
            return false;
        }
    }

    // Menghapus Prodi
    public function delete(int $id): bool
    {
        try {
            $prodi = Prodi::findOrFail($id);
            return $prodi->delete();
        } catch (Exception $e) {
            return false;
        }
    }
}
