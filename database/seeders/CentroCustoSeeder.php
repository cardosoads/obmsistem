<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CentroCusto;
use App\Models\Base;
use App\Models\Marca;
use Illuminate\Support\Facades\DB;

class CentroCustoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa a tabela antes de inserir novos dados
        DB::table('centros_custo')->delete();
        
        // Buscar bases e marcas existentes
        $bases = Base::all();
        $marcas = Marca::all();
        
        // Se não houver bases ou marcas, criar alguns dados básicos
        if ($bases->isEmpty()) {
            $this->command->warn('Nenhuma base encontrada. Execute o BaseSeeder primeiro.');
            return;
        }
        
        if ($marcas->isEmpty()) {
            $this->command->warn('Nenhuma marca encontrada. Execute o MarcaSeeder primeiro.');
            return;
        }
        
        // Dados de exemplo para centros de custo
        $centrosCusto = [
            [
                'name' => 'Centro Administrativo SP',
                'codigo' => 'ADM-SP-001',
                'description' => 'Centro de custo administrativo da filial São Paulo',
                'cliente_omie_id' => '12345',
                'cliente_nome' => 'Empresa ABC Ltda',
                'base_id' => $bases->where('sigla', 'SP')->first()?->id ?? $bases->first()->id,
                'marca_id' => $marcas->where('name', 'TOYOTA')->first()?->id ?? $marcas->first()->id,
                'active' => true,
            ],
            [
                'name' => 'Vendas Rio de Janeiro',
                'codigo' => 'VND-RJ-001',
                'description' => 'Centro de custo de vendas da filial Rio de Janeiro',
                'cliente_omie_id' => '12346',
                'cliente_nome' => 'João Silva',
                'base_id' => $bases->where('sigla', 'RJ')->first()?->id ?? $bases->skip(1)->first()?->id ?? $bases->first()->id,
                'marca_id' => $marcas->where('name', 'FORD')->first()?->id ?? $marcas->skip(1)->first()?->id ?? $marcas->first()->id,
                'active' => true,
            ],
            [
                'name' => 'Operações Belo Horizonte',
                'codigo' => 'OPR-BH-001',
                'description' => 'Centro de custo operacional da filial Belo Horizonte',
                'cliente_omie_id' => '12347',
                'cliente_nome' => 'Maria Santos Comércio',
                'base_id' => $bases->where('sigla', 'BH')->first()?->id ?? $bases->skip(2)->first()?->id ?? $bases->first()->id,
                'marca_id' => $marcas->where('name', 'VOLKSWAGEN')->first()?->id ?? $marcas->skip(2)->first()?->id ?? $marcas->first()->id,
                'active' => true,
            ],
            [
                'name' => 'Marketing Salvador',
                'codigo' => 'MKT-SSA-001',
                'description' => 'Centro de custo de marketing da filial Salvador',
                'cliente_omie_id' => '12348',
                'cliente_nome' => 'Pedro Oliveira',
                'base_id' => $bases->where('sigla', 'SSA')->first()?->id ?? $bases->skip(3)->first()?->id ?? $bases->first()->id,
                'marca_id' => $marcas->where('name', 'CHEVROLET')->first()?->id ?? $marcas->skip(3)->first()?->id ?? $marcas->first()->id,
                'active' => true,
            ],
            [
                'name' => 'TI Brasília',
                'codigo' => 'TI-BSB-001',
                'description' => 'Centro de custo de tecnologia da informação - Brasília',
                'cliente_omie_id' => '12349',
                'cliente_nome' => 'Tech Solutions Ltda',
                'base_id' => $bases->where('sigla', 'BSB')->first()?->id ?? $bases->skip(4)->first()?->id ?? $bases->first()->id,
                'marca_id' => $marcas->where('name', 'HONDA')->first()?->id ?? $marcas->skip(4)->first()?->id ?? $marcas->first()->id,
                'active' => true,
            ],
            [
                'name' => 'Logística Porto Alegre',
                'codigo' => 'LOG-POA-001',
                'description' => 'Centro de custo de logística da filial Porto Alegre',
                'cliente_omie_id' => '12350',
                'cliente_nome' => 'Logística Express',
                'base_id' => $bases->where('sigla', 'POA')->first()?->id ?? $bases->skip(5)->first()?->id ?? $bases->first()->id,
                'marca_id' => $marcas->where('name', 'HYUNDAI')->first()?->id ?? $marcas->skip(5)->first()?->id ?? $marcas->first()->id,
                'active' => true,
            ],
            [
                'name' => 'Financeiro Curitiba',
                'codigo' => 'FIN-CWB-001',
                'description' => 'Centro de custo financeiro da filial Curitiba',
                'cliente_omie_id' => '12351',
                'cliente_nome' => 'Consultoria Financeira',
                'base_id' => $bases->where('sigla', 'CWB')->first()?->id ?? $bases->skip(6)->first()?->id ?? $bases->first()->id,
                'marca_id' => $marcas->first()->id,
                'active' => true,
            ],
            [
                'name' => 'Recursos Humanos Recife',
                'codigo' => 'RH-REC-001',
                'description' => 'Centro de custo de recursos humanos da filial Recife',
                'cliente_omie_id' => '12352',
                'cliente_nome' => 'RH Consultoria',
                'base_id' => $bases->where('sigla', 'REC')->first()?->id ?? $bases->skip(7)->first()?->id ?? $bases->first()->id,
                'marca_id' => $marcas->skip(1)->first()?->id ?? $marcas->first()->id,
                'active' => true,
            ],
            [
                'name' => 'Produção Fortaleza',
                'codigo' => 'PRD-FOR-001',
                'description' => 'Centro de custo de produção da filial Fortaleza',
                'cliente_omie_id' => '12353',
                'cliente_nome' => 'Indústria Nordeste',
                'base_id' => $bases->where('sigla', 'FOR')->first()?->id ?? $bases->skip(8)->first()?->id ?? $bases->first()->id,
                'marca_id' => $marcas->skip(2)->first()?->id ?? $marcas->first()->id,
                'active' => true,
            ],
            [
                'name' => 'Manutenção Manaus',
                'codigo' => 'MNT-MAO-001',
                'description' => 'Centro de custo de manutenção da filial Manaus',
                'cliente_omie_id' => '12354',
                'cliente_nome' => 'Manutenção Industrial',
                'base_id' => $bases->where('sigla', 'MAO')->first()?->id ?? $bases->last()->id,
                'marca_id' => $marcas->skip(3)->first()?->id ?? $marcas->first()->id,
                'active' => false, // Este está inativo
            ]
        ];
        
        foreach ($centrosCusto as $centroCustoData) {
            $centroCusto = CentroCusto::create($centroCustoData);
            
            // Atualizar campos da base automaticamente
            if ($centroCusto->base_id) {
                $centroCusto->updateBaseFields();
                $centroCusto->save();
            }
        }
        
        $this->command->info('Centros de custo criados com sucesso!');
    }
}