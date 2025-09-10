<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Combustivel;
use App\Models\Base;
use Carbon\Carbon;

class CombustivelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar algumas bases existentes
        $bases = Base::active()->limit(5)->get();
        
        if ($bases->isEmpty()) {
            // Se não houver bases, criar dados fictícios
            $baseIds = [1, 2, 3, 4, 5];
        } else {
            $baseIds = $bases->pluck('id')->toArray();
        }

        $convenios = [
            'Posto Shell',
            'Posto Ipiranga',
            'Posto BR',
            'Posto Texaco',
            'Posto Ale',
            'Posto Esso',
        ];

        $combustiveis = [];
        
        foreach ($baseIds as $baseId) {
            foreach ($convenios as $index => $convenio) {
                // Variação de preços por base e convênio
                $precoBase = 5.50 + ($index * 0.15); // Preços entre 5.50 e 6.25
                $variacao = (rand(-20, 20) / 100); // Variação de -20% a +20%
                $precoFinal = $precoBase + ($precoBase * $variacao);
                
                $combustiveis[] = [
                    'base_id' => $baseId,
                    'convenio' => $convenio,
                    'preco_litro' => round($precoFinal, 2),
                    'active' => rand(0, 10) > 1, // 90% ativos
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        // Adicionar alguns registros específicos para testes
        $combustiveisEspecificos = [
            [
                'base_id' => $baseIds[0] ?? 1,
                'convenio' => 'Posto Premium',
                'preco_litro' => 6.89,
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'base_id' => $baseIds[1] ?? 2,
                'convenio' => 'Posto Econômico',
                'preco_litro' => 5.25,
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'base_id' => $baseIds[0] ?? 1,
                'convenio' => 'Posto 24h',
                'preco_litro' => 6.15,
                'active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        $combustiveis = array_merge($combustiveis, $combustiveisEspecificos);

        foreach ($combustiveis as $combustivel) {
            Combustivel::updateOrCreate(
                [
                    'base_id' => $combustivel['base_id'],
                    'convenio' => $combustivel['convenio']
                ],
                $combustivel
            );
        }
    }
}
