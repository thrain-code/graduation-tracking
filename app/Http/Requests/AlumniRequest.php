<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlumniRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "nama_lengkap" => "required|string|max:255",
            "nim" => "required|string|max:20|unique:alumnis,nim," . $this->id,
            "jenis_kelamin" => "required|string|in:laki-laki,perempuan",
            "tahun_lulus" => "required|integer|min:1990|max:" . date('Y'),
            "prodi_id" => "required|exists:prodis,id",
            "number_phone" => "nullable|string|max:20",
            "alamat" => "nullable|string|max:500",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:6",
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "nama_lengkap.required" => "Nama lengkap tidak boleh kosong",
            "nama_lengkap.string" => "Nama lengkap harus berupa teks",
            "nama_lengkap.max" => "Nama lengkap maksimal 255 karakter",
            
            "nim.required" => "NIM tidak boleh kosong",
            "nim.string" => "NIM harus berupa teks",
            "nim.max" => "NIM maksimal 20 karakter",
            "nim.unique" => "NIM sudah digunakan oleh alumni lain",
            
            "jenis_kelamin.required" => "Jenis kelamin tidak boleh kosong",
            "jenis_kelamin.string" => "Jenis kelamin harus berupa teks",
            "jenis_kelamin.in" => "Jenis kelamin harus salah satu dari: laki-laki, perempuan",
            
            "tahun_lulus.required" => "Tahun lulus tidak boleh kosong",
            "tahun_lulus.integer" => "Tahun lulus harus berupa angka",
            "tahun_lulus.min" => "Tahun lulus minimal 1990",
            "tahun_lulus.max" => "Tahun lulus tidak boleh lebih dari tahun sekarang",
            
            "prodi_id.required" => "Program studi tidak boleh kosong",
            "prodi_id.exists" => "Program studi tidak valid",
            
            "number_phone.string" => "Nomor telepon harus berupa teks",
            "number_phone.max" => "Nomor telepon maksimal 20 karakter",
            
            "alamat.string" => "Alamat harus berupa teks",
            "alamat.max" => "Alamat maksimal 500 karakter",
            
            "email.required_if" => "Email wajib diisi jika membuat akun pengguna",
            "email.email" => "Format email tidak valid",
            "email.unique" => "Email sudah digunakan oleh pengguna lain",
            
            "password.required_if" => "Password wajib diisi jika membuat akun pengguna",
            "password.min" => "Password minimal 6 karakter",
        ];
    }
}