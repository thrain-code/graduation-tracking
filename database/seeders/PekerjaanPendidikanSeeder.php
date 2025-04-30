<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumni;
use App\Models\Pekerjaan;
use App\Models\PendidikanLanjutan;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class PekerjaanPendidikanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        Alumni::all()->each(function ($alumni) use ($faker) {
            $random = rand(1, 3);

            if ($random === 1) {
                // hanya pekerjaan
                Pekerjaan::factory(rand(1, 3))->create([
                    'alumni_id' => $alumni->id,
                    'nama_perusahaan' => $faker->company,
                    'jabatan' => $faker->jobTitle,
                    'gaji' => $faker->numberBetween(3000000, 15000000),
                    'tahun_mulai' => $faker->year,
                    'tahun_selesai' => $faker->optional()->year,
                    'masih_bekerja' => $faker->boolean,
                    'created_at' => now(),
                ]);
            } elseif ($random === 2) {
                // hanya pendidikan
                PendidikanLanjutan::factory(rand(1, 2))->create([
                    'alumni_id' => $alumni->id,
                    'jenjang' => $faker->randomElement(['S2', 'S3']),
                    'institusi' => $faker->company . " University",
                    'jurusan' => $faker->word,
                    'tahun_mulai' => $faker->year,
                    'tahun_selesai' => $faker->optional()->year,
                    'created_at' => now(),
                ]);
            } else {
                // keduanya
                Pekerjaan::factory(rand(1, 3))->create([
                    'alumni_id' => $alumni->id,
                    'nama_perusahaan' => $faker->company,
                    'jabatan' => $faker->jobTitle,
                    'gaji' => $faker->numberBetween(3000000, 15000000),
                    'tahun_mulai' => $faker->year,
                    'tahun_selesai' => $faker->optional()->year,
                    'masih_bekerja' => $faker->boolean,
                    'created_at' => now(),
                ]);

                PendidikanLanjutan::factory(rand(1, 2))->create([
                    'alumni_id' => $alumni->id,
                    'jenjang' => $faker->randomElement(['S2', 'S3']),
                    'institusi' => $faker->company . " University",
                    'jurusan' => $faker->word,
                    'tahun_mulai' => $faker->year,
                    'tahun_selesai' => $faker->optional()->year,
                    'created_at' => now(),
                ]);
            }
        });
    }
}
