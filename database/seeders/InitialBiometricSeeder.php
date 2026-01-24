<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialBiometricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Crear un usuario admin para que puedas entrar al panel de Vue después
    \App\Models\User::factory()->create([
        'name' => 'Admin Biometrico',
        'email' => 'admin@ejemplo.com',
        'password' => bcrypt('axelaxel'),
    ]);
}
}
