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
        // Primeiro limpar a tabela pivô para evitar problemas de chave estrangeira
        DB::table('grupo_imposto_imposto')->delete();
        DB::table('impostos')->delete();

        $impostos = [
            // Impostos básicos do Lucro Presumido
            [
                'nome' => 'ISS',
                'percentual' => 5.00,
                'descricao' => 'Imposto Sobre Serviços - Lucro Presumido',
                'ativo' => true
            ],
            [
                'nome' => 'PIS',
                'percentual' => 0.65,
                'descricao' => 'Programa de Integração Social - Lucro Presumido',
                'ativo' => true
            ],
            [
                'nome' => 'COFINS',
                'percentual' => 3.00,
                'descricao' => 'Contribuição para o Financiamento da Seguridade Social - Lucro Presumido',
                'ativo' => true
            ],
            [
                'nome' => 'CSLL',
                'percentual' => 1.44,
                'descricao' => 'Contribuição Social sobre o Lucro Líquido - Lucro Presumido',
                'ativo' => true
            ],
            [
                'nome' => 'IRPJ',
                'percentual' => 2.40,
                'descricao' => 'Imposto de Renda Pessoa Jurídica - Lucro Presumido',
                'ativo' => true
            ],
            [
                'nome' => 'AD IRPJ',
                'percentual' => 0.80,
                'descricao' => 'Adicional do Imposto de Renda Pessoa Jurídica - Lucro Presumido',
                'ativo' => true
            ],
            
            // Desonerações por ano
            [
                'nome' => 'DESONERAÇÃO 2025',
                'percentual' => -1.20,
                'descricao' => 'Desoneração da folha de pagamento para 2025',
                'ativo' => true
            ],
            [
                'nome' => 'DESONERAÇÃO 2026',
                'percentual' => -0.96,
                'descricao' => 'Desoneração da folha de pagamento para 2026',
                'ativo' => true
            ],
            [
                'nome' => 'DESONERAÇÃO 2027',
                'percentual' => -0.77,
                'descricao' => 'Desoneração da folha de pagamento para 2027',
                'ativo' => true
            ],
            [
                'nome' => 'DESONERAÇÃO 2028',
                'percentual' => -0.61,
                'descricao' => 'Desoneração da folha de pagamento para 2028',
                'ativo' => true
            ],
            
            // Imposto específico para transporte
            [
                'nome' => 'TRANSPORTE (exceto carga)',
                'percentual' => 16.00,
                'descricao' => '16% para Transporte (exceto carga)',
                'ativo' => true
            ],
            
            // Impostos totais por ano (calculados)
            [
                'nome' => 'IMPOSTO TOTAL 2025',
                'percentual' => 12.09,
                'descricao' => 'Imposto total calculado para 2025 (ISS+PIS+COFINS+CSLL+IRPJ+AD IRPJ+DESONERAÇÃO 2025)',
                'ativo' => true
            ],
            [
                'nome' => 'IMPOSTO TOTAL 2026',
                'percentual' => 12.33,
                'descricao' => 'Imposto total calculado para 2026 (ISS+PIS+COFINS+CSLL+IRPJ+AD IRPJ+DESONERAÇÃO 2026)',
                'ativo' => true
            ],
            [
                'nome' => 'IMPOSTO TOTAL 2027',
                'percentual' => 12.52,
                'descricao' => 'Imposto total calculado para 2027 (ISS+PIS+COFINS+CSLL+IRPJ+AD IRPJ+DESONERAÇÃO 2027)',
                'ativo' => true
            ],
            [
                'nome' => 'IMPOSTO TOTAL 2028',
                'percentual' => 12.68,
                'descricao' => 'Imposto total calculado para 2028 (ISS+PIS+COFINS+CSLL+IRPJ+AD IRPJ+DESONERAÇÃO 2028)',
                'ativo' => true
            ],
            [
                'nome' => 'IMPOSTO TOTAL 2029',
                'percentual' => 13.29,
                'descricao' => 'Imposto total calculado para 2029 (ISS+PIS+COFINS+CSLL+IRPJ+AD IRPJ)',
                'ativo' => true
            ]
        ];

        foreach ($impostos as $imposto) {
            Imposto::create($imposto);
        }

        $this->command->info('Impostos criados com sucesso!');
    }
}