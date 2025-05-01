<?php
namespace Database\Factories;

use App\Models\Alumni;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Gunakan distribusi yang lebih terkontrol untuk status
        $statuses = ['bekerja', 'kuliah', 'wirausaha', 'mengurus keluarga'];
        $type = $this->faker->randomElement($statuses); // Peluang merata untuk setiap status
        $tahunMulai = $this->faker->numberBetween(2015, date('Y'));

        $baseData = [
            'type' => $type,
            'alumni_id' => Alumni::factory(), // Akan di-override di seeder jika diperlukan
            'tahun_mulai' => $type !== 'mengurus keluarga' ? $tahunMulai : null,
            'is_active' => $this->faker->boolean(90), // 90% kemungkinan aktif untuk visibilitas
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($type === 'bekerja') {
            $baseData['nama'] = $this->faker->company();
            $baseData['jabatan'] = $this->faker->jobTitle();
            $baseData['jenis_pekerjaan'] = $this->faker->randomElement([
                'Guru PNS', 'Guru Non PNS', 'Tentor/Instruktur/Pengajar',
                'Pengelola Kursus', 'Bisnis/Berjualan', 'Karyawan Swasta', 'Tidak'
            ]);
            $baseData['gaji'] = $this->faker->numberBetween(3000000, 20000000);
            $baseData['jenjang'] = null;
        } elseif ($type === 'kuliah') {
            $baseData['nama'] = $this->faker->randomElement(['Universitas', 'Institut', 'Politeknik', 'Sekolah Tinggi']) . ' ' . $this->faker->word() . ' ' . $this->faker->city();
            $baseData['jenjang'] = $this->faker->randomElement(['S1', 'S2', 'S3']);
            $baseData['gaji'] = null;
            $baseData['jabatan'] = null;
            $baseData['jenis_pekerjaan'] = null;
        } elseif ($type === 'wirausaha') {
            $baseData['nama'] = $this->faker->company();
            $baseData['jenis_pekerjaan'] = $this->faker->randomElement([
                'Bisnis Retail', 'Jasa Konsultasi', 'Freelancer', 'Kuliner', 'Teknologi Startup'
            ]);
            $baseData['gaji'] = $this->faker->numberBetween(2000000, 30000000);
            $baseData['jabatan'] = null;
            $baseData['jenjang'] = null;
        } else { // mengurus keluarga
            $baseData['nama'] = null;
            $baseData['jabatan'] = null;
            $baseData['jenjang'] = null;
            $baseData['jenis_pekerjaan'] = null;
            $baseData['gaji'] = null;
        }

        return $baseData;
    }

    /**
     * Status bekerja.
     */
    public function bekerja(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'bekerja',
                'nama' => $this->faker->company(),
                'jabatan' => $this->faker->jobTitle(),
                'jenis_pekerjaan' => $this->faker->randomElement([
                    'Guru PNS', 'Guru Non PNS', 'Tentor/Instruktur/Pengajar',
                    'Pengelola Kursus', 'Bisnis/Berjualan', 'Karyawan Swasta', 'Tidak'
                ]),
                'gaji' => $this->faker->numberBetween(3000000, 20000000),
                'jenjang' => null,
                'tahun_mulai' => $this->faker->numberBetween(2015, date('Y')),
                'is_active' => $this->faker->boolean(90),
            ];
        });
    }

    /**
     * Status kuliah.
     */
    public function kuliah(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'kuliah',
                'nama' => $this->faker->randomElement(['Universitas', 'Institut', 'Politeknik', 'Sekolah Tinggi']) . ' ' . $this->faker->word() . ' ' . $this->faker->city(),
                'jenjang' => $this->faker->randomElement(['S1', 'S2', 'S3']),
                'jabatan' => null,
                'jenis_pekerjaan' => null,
                'gaji' => null,
                'tahun_mulai' => $this->faker->numberBetween(2015, date('Y')),
                'is_active' => $this->faker->boolean(90),
            ];
        });
    }

    /**
     * Status wirausaha.
     */
    public function wirausaha(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'wirausaha',
                'nama' => $this->faker->company(),
                'jenis_pekerjaan' => $this->faker->randomElement([
                    'Bisnis Retail', 'Jasa Konsultasi', 'Freelancer', 'Kuliner', 'Teknologi Startup'
                ]),
                'gaji' => $this->faker->numberBetween(2000000, 30000000),
                'jabatan' => null,
                'jenjang' => null,
                'tahun_mulai' => $this->faker->numberBetween(2015, date('Y')),
                'is_active' => $this->faker->boolean(90),
            ];
        });
    }

    /**
     * Status mengurus keluarga.
     */
    public function mengurusKeluarga(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'mengurus keluarga',
                'nama' => null,
                'jabatan' => null,
                'jenjang' => null,
                'jenis_pekerjaan' => null,
                'gaji' => null,
                'tahun_mulai' => null,
                'is_active' => $this->faker->boolean(90),
            ];
        });
    }

    /**
     * Status aktif.
     */
    public function active(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true,
            ];
        });
    }

    /**
     * Status tidak aktif.
     */
    public function inactive(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }
}
