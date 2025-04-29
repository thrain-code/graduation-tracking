<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alumni;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AlumniUserSeeder extends Seeder
{
    public function run(): void
    {
        Alumni::factory(1000)->create()->each(function ($alumni) {
            User::create([
                'alumni_id' => $alumni->id,
                'email' => fake('id_ID')->unique()->safeEmail(),
                'name' => $alumni->nim,
                'password' => Hash::make('password'),
                'is_admin' => false,
                'created_at' => now(),
            ]);
        });
    }
}
