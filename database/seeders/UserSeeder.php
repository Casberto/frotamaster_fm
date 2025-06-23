<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria o Super Administrador do Sistema
        User::create([
            'name' => 'Admin Frotamaster',
            'email' => 'admin@frotamaster.com',
            'password' => Hash::make('password'), // Mude para uma senha segura!
            'id_empresa' => null, // Não pertence a nenhuma empresa
            'role' => 'super-admin',
            'email_verified_at' => now(),
        ]);
    }
}
