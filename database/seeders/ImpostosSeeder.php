<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Imposto;
use Illuminate\Support\Facades\DB;

class ImpostosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar tabela antes de inserir
        DB::table('impostos')->truncate();

        $impostos = [
            // Impostos básicos do Lucro Presumido
            [
                'name' => 'ISS',
                'percentual' => 5.00,
                'description' => 'Imposto Sobre Serviços - Lucro Presumido',
                'active' => true
            ],
            [
                'name' => 'PIS',
                'percentual' => 0.65,
                'description' => 'Programa de Integração Social - Lucro Presumido',
                'active' => true
            ],
            [
                'name' => 'COFINS',
                'percentual' => 3.00,
                'description' => 'Contribuição para o Financiamento da Seguridade Social - Lucro Presumido',
                'active' => true
            ],
            [
                'name' => 'CSLL',
                'percentual' => 1.44,
                'description' => 'Contribuição Social sobre o Lucro Líquido - Lucro Presumido',
                'active' => true
            ],
            [
                'name' => 'IRPJ',
                'percentual' => 2.40,
                'description' => 'Imposto de Renda Pessoa Jurídica - Lucro Presumido',
                'active' => true
            ],
            [
                'name' => 'AD IRPJ',
                'percentual' => 0.80,
                'description' => 'Adicional do Imposto de Renda Pessoa Jurídica - Lucro Presumido',
                'active' => true
            ],
            
            // Desonerações por ano
            [
                'name' => 'DESONERAÇÃO 2025',
                'percentual' => -1.20,
                'description' => 'Desoneração da folha de pagamento para 2025',
                'active' => true
            ],
            [
                'name' => 'DESONERAÇÃO 2026',
                'percentual' => -0.96,
                'description' => 'Desoneração da folha de pagamento para 2026',
                'active' => true
            ],
            [
                'name' => 'DESONERAÇÃO 2027',
                'percentual' => -0.77,
                'description' => 'Desoneração da folha de pagamento para 2027',
                'active' => true
            ],
            [
                'name' => 'DESONERAÇÃO 2028',
                'percentual' => -0.61,
                'description' => 'Desoneração da folha de pagamento para 2028',
                'active' => true
            ],
            
            // Imposto específico para transporte
            [
                'name' => 'TRANSPORTE (exceto carga)',
                'percentual' => 16.00,
                'description' => '16% para Transporte (exceto carga)',
                'active' => true
            ],
            
            // Impostos totais por ano (calculados)
            [
                'name' => 'IMPOSTO TOTAL 2025',
                'percentual' => 12.09,
                'description' => 'Imposto total calculado para 2025 (ISS+PIS+COFINS+CSLL+IRPJ+AD IRPJ+DESONERAÇÃO 2025)',
                'active' => true
            ],
            [
                'name' => 'IMPOSTO TOTAL 2026',
                'percentual' => 12.33,
                'description' => 'Imposto total calculado para 2026 (ISS+PIS+COFINS+CSLL+IRPJ+AD IRPJ+DESONERAÇÃO 2026)',
                'active' => true
            ],
            [
                'name' => 'IMPOSTO TOTAL 2027',
                'percentual' => 12.52,
                'description' => 'Imposto total calculado para 2027 (ISS+PIS+COFINS+CSLL+IRPJ+AD IRPJ+DESONERAÇÃO 2027)',
                'active' => true
            ],
            [
                'name' => 'IMPOSTO TOTAL 2028',
                'percentual' => 12.68,
                'description' => 'Imposto total calculado para 2028 (ISS+PIS+COFINS+CSLL+IRPJ+AD IRPJ+DESONERAÇÃO 2028)',
                'active' => true
            ],
            [
                'name' => 'IMPOSTO TOTAL 2029',
                'percentual' => 13.29,
                'description' => 'Imposto total calculado para 2029 (ISS+PIS+COFINS+CSLL+IRPJ+AD IRPJ)',
                'active' => true
            ]
        ];

        foreach ($impostos as $imposto) {
            Imposto::create($imposto);
        }

        $this->command->info('Impostos criados com sucesso!');
    }
}