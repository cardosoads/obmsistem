<?php

namespace Database\Seeders;

use App\Models\RecursoHumano;
use App\Models\Base;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecursoHumanoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar algumas bases para relacionamento
        $bases = Base::active()->take(3)->get();
        
        if ($bases->isEmpty()) {
            $this->command->warn('Nenhuma base encontrada. Execute o BaseSeeder primeiro.');
            return;
        }

        $recursosHumanos = [
            [
                'tipo_contratacao' => 'CLT',
                'cargo' => 'Motorista',
                'base_id' => $bases->first()->id,
                'base_salarial' => 1500.00,
                'salario_base' => 1500.00,
                'insalubridade' => 150.00,
                'periculosidade' => 0.00,
                'horas_extras' => 200.00,
                'adicional_noturno' => 100.00,
                'extras' => 50.00,
                'vale_transporte' => 180.00,
                'beneficios' => 300.00,
                'percentual_encargos' => 68.50, // Percentual típico de encargos CLT
                'percentual_beneficios' => 15.00,
                'active' => true,
            ],
            [
                'tipo_contratacao' => 'CLT',
                'cargo' => 'Motorista Líder',
                'base_id' => $bases->first()->id,
                'base_salarial' => 2000.00,
                'salario_base' => 2000.00,
                'insalubridade' => 200.00,
                'periculosidade' => 150.00,
                'horas_extras' => 300.00,
                'adicional_noturno' => 150.00,
                'extras' => 100.00,
                'vale_transporte' => 180.00,
                'beneficios' => 400.00,
                'percentual_encargos' => 68.50,
                'percentual_beneficios' => 18.00,
                'active' => true,
            ],
            [
                'tipo_contratacao' => 'PJ',
                'cargo' => 'Motorista',
                'base_id' => $bases->count() > 1 ? $bases->get(1)->id : $bases->first()->id,
                'base_salarial' => 2500.00,
                'salario_base' => 2500.00,
                'insalubridade' => 0.00,
                'periculosidade' => 0.00,
                'horas_extras' => 0.00,
                'adicional_noturno' => 0.00,
                'extras' => 0.00,
                'vale_transporte' => 0.00,
                'beneficios' => 0.00,
                'percentual_encargos' => 0.00, // PJ não tem encargos
                'percentual_beneficios' => 0.00,
                'active' => true,
            ],
            [
                'tipo_contratacao' => 'Terceirizado',
                'cargo' => 'Ajudante',
                'base_id' => $bases->count() > 2 ? $bases->get(2)->id : $bases->first()->id,
                'base_salarial' => 1200.00,
                'salario_base' => 1200.00,
                'insalubridade' => 120.00,
                'periculosidade' => 0.00,
                'horas_extras' => 150.00,
                'adicional_noturno' => 80.00,
                'extras' => 30.00,
                'vale_transporte' => 150.00,
                'beneficios' => 200.00,
                'percentual_encargos' => 45.00, // Terceirizado tem encargos menores
                'percentual_beneficios' => 12.00,
                'active' => true,
            ],
            [
                'tipo_contratacao' => 'CLT',
                'cargo' => 'Supervisor',
                'base_id' => $bases->first()->id,
                'base_salarial' => 3500.00,
                'salario_base' => 3500.00,
                'insalubridade' => 0.00,
                'periculosidade' => 0.00,
                'horas_extras' => 400.00,
                'adicional_noturno' => 0.00,
                'extras' => 200.00,
                'vale_transporte' => 200.00,
                'beneficios' => 600.00,
                'percentual_encargos' => 68.50,
                'percentual_beneficios' => 20.00,
                'active' => true,
            ],
            [
                'tipo_contratacao' => 'Temporário',
                'cargo' => 'Motorista',
                'base_id' => $bases->count() > 1 ? $bases->get(1)->id : $bases->first()->id,
                'base_salarial' => 1400.00,
                'salario_base' => 1400.00,
                'insalubridade' => 140.00,
                'periculosidade' => 0.00,
                'horas_extras' => 180.00,
                'adicional_noturno' => 90.00,
                'extras' => 40.00,
                'vale_transporte' => 160.00,
                'beneficios' => 250.00,
                'percentual_encargos' => 55.00, // Temporário tem encargos intermediários
                'percentual_beneficios' => 10.00,
                'active' => true,
            ],
        ];

        foreach ($recursosHumanos as $recursoData) {
            RecursoHumano::create($recursoData);
        }

        $this->command->info('Recursos Humanos criados com sucesso!');
        $this->command->info('Total de registros: ' . count($recursosHumanos));
        
        // Exibir resumo dos dados criados
        $this->command->table(
            ['Tipo Contratação', 'Cargo', 'Salário Base', 'Custo Total'],
            collect($recursosHumanos)->map(function ($recurso) {
                $rh = RecursoHumano::where('cargo', $recurso['cargo'])
                                  ->where('tipo_contratacao', $recurso['tipo_contratacao'])
                                  ->first();
                return [
                    $recurso['tipo_contratacao'],
                    $recurso['cargo'],
                    'R$ ' . number_format($recurso['salario_base'], 2, ',', '.'),
                    'R$ ' . number_format($rh ? $rh->custo_total_mao_obra : 0, 2, ',', '.'),
                ];
            })->toArray()
        );
    }
}
