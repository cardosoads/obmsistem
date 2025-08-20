<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa a tabela antes de inserir novos dados
        DB::table('marcas')->delete();
        
        // Dados de exemplo para marcas
        $marcas = [
            [
                'name' => 'TOYOTA',
                'mercado' => 'AUTOMOTIVO',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FORD',
                'mercado' => 'AUTOMOTIVO',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VOLKSWAGEN',
                'mercado' => 'AUTOMOTIVO',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CHEVROLET',
                'mercado' => 'AUTOMOTIVO',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HONDA',
                'mercado' => 'AUTOMOTIVO',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HYUNDAI',
                'mercado' => 'AUTOMOTIVO',
                'active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'NISSAN',
                'mercado' => 'AUTOMOTIVO',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FIAT',
                'mercado' => 'AUTOMOTIVO',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'RENAULT',
                'mercado' => 'AUTOMOTIVO',
                'active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PEUGEOT',
                'mercado' => 'AUTOMOTIVO',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Insere os dados na tabela
        DB::table('marcas')->insert($marcas);
        
        $this->command->info('Marcas criadas com sucesso!');
    }
}