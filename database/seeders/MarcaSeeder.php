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
        
        // Dados das marcas
        $marcas = [
            [
                'name' => 'Lunav',
                'mercado' => 'APOIO',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Santa Luzia',
                'mercado' => 'PRIVADO',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ghanem',
                'mercado' => 'PUBLICO',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dasa',
                'mercado' => 'APOIO',
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