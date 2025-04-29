<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Prodi;

class AlumniFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_lengkap' => $this->faker->name(),
            'nim' => $this->faker->unique()->numerify('##########'),
            'jenis_kelamin' => $this->faker->randomElement(['laki-laki', 'perempuan']),
            'tahun_lulus' => "2019",
            'prodi_id' => Prodi::first()->id,
            'number_phone' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
            'created_at' => now(),
        ];
    }
}
