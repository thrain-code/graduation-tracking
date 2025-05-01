<?php
namespace Database\Seeders;

use App\Models\Alumni;
use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusAlumniSeeder extends Seeder
{
    public function run(): void
    {
        $alumnis = Alumni::all();

        // Status yang akan dibagikan merata
        $statusTypes = ['bekerja', 'kuliah', 'wirausaha', 'mengurus keluarga'];
        $typeIndex = 0;

        foreach ($alumnis as $index => $alumni) {
            // 70% kemungkinan alumni memiliki status
            if (rand(1, 100) <= 70) {
                $type = $statusTypes[$typeIndex % count($statusTypes)];
                Status::factory()
                    ->{$type}() // pakai state method dari factory
                    ->create([
                        'alumni_id' => $alumni->id,
                    ]);
                $typeIndex++; // pindah ke type selanjutnya untuk distribusi merata
            }
        }
    }
}
