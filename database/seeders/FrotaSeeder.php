<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Frota;
use App\Models\TipoVeiculo;
use Carbon\Carbon;

class FrotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar tipos de veículos existentes
        $tipoCarroCompacto = TipoVeiculo::where('codigo', 'CAR001')->first();
        $tipoCarroMedio = TipoVeiculo::where('codigo', 'CAR002')->first();
        $tipoSuv = TipoVeiculo::where('codigo', 'SUV001')->first();
        $tipoVan = TipoVeiculo::where('codigo', 'VAN001')->first();
        $tipoCaminhonete = TipoVeiculo::where('codigo', 'CAM001')->first();
        $tipoMoto = TipoVeiculo::where('codigo', 'MOT001')->first();

        $frotas = [
            [
                'tipo_veiculo_id' => $tipoCarroCompacto?->id ?? 1,
                'fipe' => 45000.00,
                'percentual_fipe' => 85.5,
                'aluguel_carro' => 1200.00,
                'rastreador' => 89.90,
                'provisoes_avarias' => 150.00,
                'provisao_desmobilizacao' => 200.00,
                'provisao_diaria_rac' => 25.00,
                'custo_total' => 0, // Será calculado automaticamente
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tipo_veiculo_id' => $tipoCarroMedio?->id ?? 2,
                'fipe' => 65000.00,
                'percentual_fipe' => 82.3,
                'aluguel_carro' => 1500.00,
                'rastreador' => 89.90,
                'provisoes_avarias' => 200.00,
                'provisao_desmobilizacao' => 250.00,
                'provisao_diaria_rac' => 30.00,
                'custo_total' => 0, // Será calculado automaticamente
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tipo_veiculo_id' => $tipoSuv?->id ?? 3,
                'fipe' => 85000.00,
                'percentual_fipe' => 80.0,
                'aluguel_carro' => 1800.00,
                'rastreador' => 89.90,
                'provisoes_avarias' => 250.00,
                'provisao_desmobilizacao' => 300.00,
                'provisao_diaria_rac' => 35.00,
                'custo_total' => 0, // Será calculado automaticamente
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tipo_veiculo_id' => $tipoVan?->id ?? 4,
                'fipe' => 120000.00,
                'percentual_fipe' => 75.5,
                'aluguel_carro' => 2200.00,
                'rastreador' => 89.90,
                'provisoes_avarias' => 350.00,
                'provisao_desmobilizacao' => 400.00,
                'provisao_diaria_rac' => 45.00,
                'custo_total' => 0, // Será calculado automaticamente
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tipo_veiculo_id' => $tipoCaminhonete?->id ?? 5,
                'fipe' => 95000.00,
                'percentual_fipe' => 78.2,
                'aluguel_carro' => 1900.00,
                'rastreador' => 89.90,
                'provisoes_avarias' => 280.00,
                'provisao_desmobilizacao' => 320.00,
                'provisao_diaria_rac' => 40.00,
                'custo_total' => 0, // Será calculado automaticamente
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tipo_veiculo_id' => $tipoMoto?->id ?? 6,
                'fipe' => 8500.00,
                'percentual_fipe' => 90.0,
                'aluguel_carro' => 350.00,
                'rastreador' => 45.00,
                'provisoes_avarias' => 50.00,
                'provisao_desmobilizacao' => 75.00,
                'provisao_diaria_rac' => 15.00,
                'custo_total' => 0, // Será calculado automaticamente
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($frotas as $frota) {
            Frota::create($frota);
        }
    }
}
