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
            Log::error('Error adding user: ' . $e->getMessage());
            return false;
        }
    }
    
    // Menampilkan user berdasarkan ID
    public function showById(int $id)
    {
        return User::findOrFail($id);
    }
    
    // Mencari user berdasarkan email
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
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
            Log::error('Error updating user: ' . $e->getMessage());
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
            Log::error('Error deleting user: ' . $e->getMessage());
            return false;
        }
    }
    
    // Membuat user admin
    public function createAdmin(array $data): bool
    {
        try {
            $data['password'] = Hash::make($data['password']);
            $data['is_admin'] = true;
            
            User::create($data);
            return true;
        } catch (Exception $e) {
            Log::error('Error creating admin: ' . $e->getMessage());
            return false;
        }
    }
    
    // Check apakah user adalah admin
    public function isAdmin(int $userId): bool
    {
        try {
            $user = User::findOrFail($userId);
            return (bool) $user->is_admin;
        } catch (Exception $e) {
            return false;
        }
    }
}