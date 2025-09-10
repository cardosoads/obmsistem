<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoVeiculo;
use Carbon\Carbon;

class TipoVeiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [
                'codigo' => 'CAR001',
                'consumo_km_litro' => 12.5,
                'tipo_combustivel' => 'Gasolina',
                'descricao' => 'Carro de Passeio Compacto',
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => 'CAR002',
                'consumo_km_litro' => 10.8,
                'tipo_combustivel' => 'Flex',
                'descricao' => 'Carro de Passeio Médio',
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => 'SUV001',
                'consumo_km_litro' => 8.5,
                'tipo_combustivel' => 'Gasolina',
                'descricao' => 'SUV Compacto',
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => 'VAN001',
                'consumo_km_litro' => 7.2,
                'tipo_combustivel' => 'Diesel',
                'descricao' => 'Van de Transporte',
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => 'CAM001',
                'consumo_km_litro' => 6.8,
                'tipo_combustivel' => 'Diesel',
                'descricao' => 'Caminhonete',
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'codigo' => 'MOT001',
                'consumo_km_litro' => 35.0,
                'tipo_combustivel' => 'Gasolina',
                'descricao' => 'Motocicleta 150cc',
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($tipos as $tipo) {
            TipoVeiculo::updateOrCreate(
                ['codigo' => $tipo['codigo']],
                $tipo
            );
        }
    }
}
