<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    // Mengambil semua user
    public function getAll()
    {
        return User::all();
    }

    // Menambahkan user baru
    public function add(array $data): bool
    {
        try {
            // Pastikan password dienkripsi
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);
            return $user ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    // Menampilkan user berdasarkan ID
    public function showById(int $id)
    {
        return User::findOrFail($id);
    }

    // Update data user
    public function update(int $id, array $data): bool
    {
        try {
            $user = User::findOrFail($id);

            // Enkripsi ulang password jika dikirim
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            return $user->update($data);
        } catch (Exception $e) {
            return false;
        }
    }

    // Menghapus user
    public function delete(int $id): bool
    {
        try {
            $user = User::findOrFail($id);
            return $user->delete();
        } catch (Exception $e) {
            return false;
        }
    }

    // Membuat user admin
    public function createAdmin(array $data): bool
    {
        try {
            $data['password'] = Hash::make($data['password']);
            $data['is_admin'] = true;
            $data['alumni_id'] = null;

            User::create($data);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
