<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumni;
use App\Models\Pekerjaan;
use App\Models\PendidikanLanjutan;

class PekerjaanPendidikanSeeder extends Seeder
{
    public function run(): void
    {
        Alumni::all()->each(function ($alumni) {
            $random = rand(1, 3);

            if ($random === 1) {
                // hanya pekerjaan
                Pekerjaan::factory(rand(1, 3))->create([
                    'alumni_id' => $alumni->id,
                ]);
            } elseif ($random === 2) {
                // hanya pendidikan
                PendidikanLanjutan::factory(rand(1, 2))->create([
                    'alumni_id' => $alumni->id,
                ]);
            } else {
                // keduanya
                Pekerjaan::factory(rand(1, 3))->create([
                    'alumni_id' => $alumni->id,
                ]);

                PendidikanLanjutan::factory(rand(1, 2))->create([
                    'alumni_id' => $alumni->id,
                ]);
            }
        });
    }
}
