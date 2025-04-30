<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi;
use App\Models\User;
use App\Models\Alumni;
use App\Models\Status;
use Illuminate\Support\Facades\Hash;

class UserAlumniDanStatusSeeder extends Seeder
{
    public function run(): void
    {


        // Buat 20 data alumni dengan user dan status
        for ($i = 0; $i < 20; $i++) {
            // Buat user menggunakan UserFactory
            $user = User::factory()->create([
                'is_admin' => false,
                'password' => Hash::make('password'),
            ]);

            // Buat alumni menggunakan AlumniFactory
            $alumni = Alumni::factory()->create([
                'user_id' => $user->id,
                'prodi_id' => Prodi::first()->id,
                'jenis_kelamin' => fake()->randomElement(['laki-laki', 'perempuan']), // Konsisten dengan laki-laki
                'tahun_lulus' => fake()->numberBetween(2019, 2024),
            ]);

            // Tentukan apakah alumni memiliki status (70% kemungkinan memiliki status)
            if (fake()->boolean(70)) {
                // Buat status menggunakan StatusFactory
                Status::factory()
                    ->state(function () {
                        $isBekerja = fake()->boolean();
                        return [
                            'type' => $isBekerja ? 'bekerja' : 'kuliah',
                            'jenjang' => $isBekerja ? null : fake()->randomElement(['S1', 'S2', 'S3']),
                            'jabatan' => $isBekerja ? fake()->jobTitle() : null,
                            'gaji' => $isBekerja ? fake()->numberBetween(3000000, 15000000) : null,
                        ];
                    })
                    ->create([
                        'alumni_id' => $alumni->id,
                        'tahun_mulai' => fake()->numberBetween($alumni->tahun_lulus, 2024),
                        'is_active' => fake()->boolean(80), // 80% kemungkinan aktif
                    ]);
            }
        }
    }
}
