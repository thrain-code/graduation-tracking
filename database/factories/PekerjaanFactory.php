<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Alumni;

class PekerjaanFactory extends Factory
{
    public function definition(): array
    {
        $tahunMulai = $this->faker->numberBetween(2019, 2024);
        $masihBekerja = $this->faker->boolean();

        return [
            'alumni_id' => Alumni::inRandomOrder()->first()->id,
            'nama_perusahaan' => $this->faker->company,
            'jabatan' => $this->faker->jobTitle,
            'gaji' => $this->faker->randomFloat(2, 3000000, 15000000),
            'tahun_mulai' => $tahunMulai,
            'tahun_selesai' => $masihBekerja ? null : $this->faker->numberBetween($tahunMulai + 1, 2025),
            'masih_bekerja' => $masihBekerja,
            'created_at' => now(),
        ];
    }
}
