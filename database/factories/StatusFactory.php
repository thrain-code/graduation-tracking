<?php
namespace Database\Factories;

use App\Models\Alumni;
use App\Models\Status;
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
        $isBekerja = $this->faker->boolean();
        $tahunMulai = $this->faker->numberBetween(2019, 2024);
        
        $baseData = [
            'nama' => $isBekerja 
                ? $this->faker->company() 
                : $this->faker->randomElement(['Universitas', 'Institut', 'Politeknik', 'Sekolah Tinggi']) . ' ' . $this->faker->word() . ' ' . $this->faker->city(),
            'type' => $isBekerja ? 'bekerja' : 'kuliah',
            'alumni_id' => Alumni::inRandomOrder()->first()->id ?? 1,
            'tahun_mulai' => $tahunMulai,
            'is_active' => $this->faker->boolean(70), // 70% kemungkinan aktif
            'created_at' => now(),
        ];
        
        // Tambahkan data khusus untuk status bekerja
        if ($isBekerja) {
            $baseData['jabatan'] = $this->faker->jobTitle();
            $baseData['gaji'] = $this->faker->numberBetween(3000000, 15000000);
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
                'nama' => $this->faker->company(),
                'type' => 'bekerja',
                'jabatan' => $this->faker->jobTitle(),
                'gaji' => $this->faker->numberBetween(3000000, 15000000),
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
                'nama' => $this->faker->randomElement(['Universitas', 'Institut', 'Politeknik', 'Sekolah Tinggi']) . ' ' . $this->faker->word() . ' ' . $this->faker->city(),
                'type' => 'kuliah',
                'jabatan' => null,
                'gaji' => null,
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