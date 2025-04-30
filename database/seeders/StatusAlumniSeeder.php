<?php

namespace Database\Seeders;

use App\Models\Alumni;
use App\Models\Status;
use App\Models\StatusAlumni;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StatusAlumniSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        Alumni::all()->each(function ($alumni) use ($faker) {
            $type = $faker->randomElement(['kuliah', 'bekerja']);

            Status::create([
                'alumni_id' => $alumni->id,
                'type' => $type,
                'jenjang' => $type === 'kuliah' ? 'S2' : null,
                'nama' => $type === 'kuliah'
                    ? $faker->randomElement(['UI', 'UGM', 'ITB', 'IPB'])
                    : $faker->company(),
                'tahun_mulai' => $faker->numberBetween(2019, 2024),
                'created_at' => now(),
            ]);
        });
    }
}
