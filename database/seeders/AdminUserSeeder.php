<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin se não existir
        User::firstOrCreate(
            ['email' => 'admin@obmsistem.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@obmsistem.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Criar usuário manager de teste
        User::firstOrCreate(
            ['email' => 'manager@obmsistem.com'],
            [
                'name' => 'Gerente Teste',
                'email' => 'manager@obmsistem.com',
                'password' => Hash::make('manager123'),
                'role' => 'manager',
                'email_verified_at' => now(),
            ]
        );

        // Criar usuário comum de teste
        User::firstOrCreate(
            ['email' => 'user@obmsistem.com'],
            [
                'name' => 'Usuário Teste',
                'email' => 'user@obmsistem.com',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}
