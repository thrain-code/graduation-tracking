<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumni;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class AlumniUserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $prodiId = Prodi::first()->id;
        $startYear = 2019;

        for ($i = 0; $i < 20; $i++) {
            $user = User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'is_admin' => false,
                'created_at' => now(),
            ]);

            $tahunLulus = $startYear + ($i % 6);

            Alumni::create([
                'user_id' => $user->id,
                'nama_lengkap' => $user->name,
                'nim' => $faker->unique()->numerify('##########'),
                'jenis_kelamin' => $faker->randomElement(['laki-laki', 'perempuan']),
                'tahun_lulus' => $tahunLulus,
                'prodi_id' => $prodiId,
                'number_phone' => $faker->phoneNumber(),
                'alamat' => $faker->address(),
                'created_at' => now(),
            ]);
        }
    }
}
