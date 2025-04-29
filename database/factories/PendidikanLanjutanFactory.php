<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Alumni;

class PendidikanLanjutanFactory extends Factory
{
    public function definition(): array
    {
        $tahunMulai = $this->faker->numberBetween(2018, 2023);
        $tahunSelesai = $this->faker->numberBetween($tahunMulai + 1, 2025);

        return [
            'alumni_id' => Alumni::inRandomOrder()->first()->id,
            'jenjang' => $this->faker->randomElement(['S2', 'S1', 'D3']),
            'institusi' => $this->faker->company . ' University',
            'jurusan' => $this->faker->word . ' Engineering',
            'tahun_mulai' => $tahunMulai,
            'tahun_selesai' => $tahunSelesai,
            'created_at' => now(),
        ];
    }
}
