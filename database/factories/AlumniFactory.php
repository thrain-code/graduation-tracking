<?php

namespace Database\Factories;

use App\Models\Prodi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alumni>
 */
class AlumniFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "nama_lengkap" => $this->faker->name(),
            "nim" => $this->faker->unique()->numerify("######"),
            "jenis_kelamin" => $this->faker->randomElement(['laki-laki', 'perempuan']),
            "tahun_lulus" => $this->faker->numberBetween(2019, 2024),
            "prodi_id" => Prodi::firstOrCreate(["prodi_name" => "PTIK"])->id,
            "user_id" => UserFactory::new()->create()->id,
            "number_phone" => $this->faker->numerify("#####"),
            "alamat" => $this->faker->city(),
            "created_at" => now(),
        ];
    }
}
