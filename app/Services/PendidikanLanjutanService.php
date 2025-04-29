<?php

namespace App\Services;

use App\Models\PendidikanLanjutan;
use Exception;
use Illuminate\Support\Facades\Log;

class PendidikanLanjutanService
{
    // Ambil semua data pendidikan lanjutan
    public function getAll()
    {
        return PendidikanLanjutan::all();
    }

    // Tambah data pendidikan lanjutan
    public function add(array $data): bool
    {
        try {
            $pendidikan = PendidikanLanjutan::create($data);
            return $pendidikan ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    // Tampilkan berdasarkan ID
    public function showById(int $id)
    {
        return PendidikanLanjutan::findOrFail($id);
    }

    // Update data
    public function update(int $id, array $data): bool
    {
        try {
            $pendidikan = PendidikanLanjutan::findOrFail($id);
            return $pendidikan->update($data);
        } catch (Exception $e) {
            return false;
        }
    }

    // Hapus data
    public function delete(int $id): bool
    {
        try {
            $pendidikan = PendidikanLanjutan::findOrFail($id);
            return $pendidikan->delete();
        } catch (Exception $e) {
            return false;
        }
    }
}
