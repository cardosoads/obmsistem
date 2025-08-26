<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GrupoImposto;
use App\Models\Imposto;
use Illuminate\Support\Facades\DB;

class GrupoImpostoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar tabelas antes de inserir
        // Primeiro, limpar a tabela pivô
        DB::table('grupo_imposto_imposto')->delete();
        // Depois, limpar a tabela de grupos
        DB::table('grupos_impostos')->delete();

        // Criar grupos de impostos
        $grupos = [
            [
                'nome' => 'Impostos Básicos - Lucro Presumido',
                'descricao' => 'Impostos básicos aplicáveis ao regime de Lucro Presumido',
                'ativo' => true,
                'impostos' => ['ISS', 'PIS', 'COFINS', 'CSLL', 'IRPJ', 'AD IRPJ']
            ],
            [
                'nome' => 'Desonerações por Ano',
                'descricao' => 'Desonerações da folha de pagamento aplicáveis por ano',
                'ativo' => true,
                'impostos' => ['DESONERAÇÃO 2025', 'DESONERAÇÃO 2026', 'DESONERAÇÃO 2027', 'DESONERAÇÃO 2028']
            ],
            [
                'nome' => 'Impostos Específicos',
                'descricao' => 'Impostos específicos para determinados tipos de serviço',
                'ativo' => true,
                'impostos' => ['TRANSPORTE (exceto carga)']
            ],
            [
                'nome' => 'Totais Calculados por Ano',
                'descricao' => 'Impostos totais pré-calculados por ano incluindo desonerações',
                'ativo' => true,
                'impostos' => ['IMPOSTO TOTAL 2025', 'IMPOSTO TOTAL 2026', 'IMPOSTO TOTAL 2027', 'IMPOSTO TOTAL 2028', 'IMPOSTO TOTAL 2029']
            ]
        ];

        foreach ($grupos as $grupoData) {
            // Criar o grupo
            $grupo = GrupoImposto::create([
                'nome' => $grupoData['nome'],
                'descricao' => $grupoData['descricao'],
                'ativo' => $grupoData['ativo']
            ]);

            // Associar impostos ao grupo usando relacionamento many-to-many
            $impostosIds = [];
            foreach ($grupoData['impostos'] as $nomeImposto) {
                $imposto = Imposto::where('nome', $nomeImposto)->first();
                if ($imposto) {
                    $impostosIds[] = $imposto->id;
                }
            }
            
            if (!empty($impostosIds)) {
                $grupo->impostos()->sync($impostosIds);
            }

            $this->command->info("Grupo '{$grupo->nome}' criado com " . count($impostosIds) . " impostos associados.");
        }

        $this->command->info('Grupos de impostos criados com sucesso!');
    }
}
