<?php

namespace Database\Factories;

use App\Models\Alumni;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Status>
 */
class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Random tipe pekerjaan
        $type = $this->faker->randomElement(["kuliah", "bekerja", "wirausaha", "mengurus keluarga"]);

        // Tentukan jenis pekerjaan, jabatan, dan gaji jika type-nya bekerja
        $jabatan = null;
        $gaji = null;
        $jenis_pekerjaan = null;
        if($type == "wirausaha"){
            // $jenis_pekerjaan = $this->faker->word();
            $gaji = $this->faker->numberBetween(3000000, 20000000);  // Misal gaji antara 3 juta sampai 20 juta
        }

        if ($type === 'bekerja') {
            // Daftar pekerjaan untuk tipe "bekerja"
            $jenis_pekerjaan = $this->faker->randomElement([
                "Guru PNS", "Guru Non PNS", "Tentor/Instruktur/Pengajar", "Pengelola Kursus",
                "Bisnis/Bejualan", "Karyawan Swasta", "Tidak"
            ]);
            // Random jabatan untuk yang bekerja
            $jabatan = $this->faker->jobTitle();
            // Generate gaji untuk pekerjaan
            $gaji = $this->faker->numberBetween(3000000, 20000000);  // Misal gaji antara 3 juta sampai 20 juta
        }

        return [
            "nama" => $this->faker->city() ." " . $this->faker->word(),
            "type" => $type,
            "alumni_id" => AlumniFactory::new()->create()->id,
            "jenis_pekerjaan" => $jenis_pekerjaan,
            "jabatan" => $jabatan,
            "gaji" => $gaji,
            "tahun_mulai" => $this->faker->numberBetween(2019, 2025),
            "is_active" => $this->faker->boolean(90),
            "created_at" => now(),
        ];
    }
}
