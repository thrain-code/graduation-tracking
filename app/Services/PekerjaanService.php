<?php

namespace App\Services;

use App\Models\Pekerjaan;
use Exception;
use Illuminate\Support\Facades\Log;

class PekerjaanService
{
    // Mengambil semua data pekerjaan
    public function getAll()
    {
        return Pekerjaan::all();
    }

    // Menambahkan data pekerjaan
    public function add(array $data): bool
    {
        try {
            $pekerjaan = Pekerjaan::create($data);
            return $pekerjaan ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    // Menampilkan pekerjaan berdasarkan ID
    public function showById(int $id)
    {
        return Pekerjaan::findOrFail($id);
    }

    // Update pekerjaan
    public function update(int $id, array $data): bool
    {
        try {
            $pekerjaan = Pekerjaan::findOrFail($id);
            return $pekerjaan->update($data);
        } catch (Exception $e) {
            return false;
        }
    }

    // Menghapus pekerjaan
    public function delete(int $id): bool
    {
        try {
            $pekerjaan = Pekerjaan::findOrFail($id);
            return $pekerjaan->delete();
        } catch (Exception $e) {
            return false;
        }
    }
}
