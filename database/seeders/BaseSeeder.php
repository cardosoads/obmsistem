<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Base;
use Illuminate\Support\Facades\DB;

class BaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar tabela antes de inserir (usando delete devido a foreign keys)
        DB::table('bases')->delete();

        $bases = [
            [
                'name' => 'São Paulo',
                'uf' => 'SP',
                'regional' => 'Sudeste',
                'sigla' => 'SP',
                'supervisor' => 'João Silva',
                'active' => true
            ],
            [
                'name' => 'Rio de Janeiro',
                'uf' => 'RJ',
                'regional' => 'Sudeste',
                'sigla' => 'RJ',
                'supervisor' => 'Maria Santos',
                'active' => true
            ],
            [
                'name' => 'Belo Horizonte',
                'uf' => 'MG',
                'regional' => 'Sudeste',
                'sigla' => 'BH',
                'supervisor' => 'Pedro Oliveira',
                'active' => true
            ],
            [
                'name' => 'Salvador',
                'uf' => 'BA',
                'regional' => 'Nordeste',
                'sigla' => 'SSA',
                'supervisor' => 'Ana Costa',
                'active' => true
            ],
            [
                'name' => 'Brasília',
                'uf' => 'DF',
                'regional' => 'Centro-Oeste',
                'sigla' => 'BSB',
                'supervisor' => 'Carlos Mendes',
                'active' => true
            ],
            [
                'name' => 'Porto Alegre',
                'uf' => 'RS',
                'regional' => 'Sul',
                'sigla' => 'POA',
                'supervisor' => 'Roberto Lima',
                'active' => true
            ],
            [
                'name' => 'Curitiba',
                'uf' => 'PR',
                'regional' => 'Sul',
                'sigla' => 'CWB',
                'supervisor' => 'Fernanda Alves',
                'active' => true
            ],
            [
                'name' => 'Recife',
                'uf' => 'PE',
                'regional' => 'Nordeste',
                'sigla' => 'REC',
                'supervisor' => 'Luiz Ferreira',
                'active' => true
            ],
            [
                'name' => 'Fortaleza',
                'uf' => 'CE',
                'regional' => 'Nordeste',
                'sigla' => 'FOR',
                'supervisor' => 'Mariana Rocha',
                'active' => true
            ],
            [
                'name' => 'Manaus',
                'uf' => 'AM',
                'regional' => 'Norte',
                'sigla' => 'MAO',
                'supervisor' => 'Ricardo Souza',
                'active' => false
            ]
        ];

        foreach ($bases as $baseData) {
            Base::create($baseData);
        }

        $this->command->info('Bases criadas com sucesso!');
    }
}