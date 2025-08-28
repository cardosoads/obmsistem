<?php

namespace App\Console\Commands;

use App\Models\CentroCusto;
use App\Services\OmieApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SincronizarCentrosCustoOmie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'omie:sync-centros-custo {--force : Força a sincronização mesmo se já foi executada recentemente}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza centros de custo (departamentos) da API Omie';

    private OmieApiService $omieService;

    public function __construct(OmieApiService $omieService)
    {
        parent::__construct();
        $this->omieService = $omieService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando sincronização de centros de custo da API Omie...');

        try {
            $totalSincronizados = 0;
            $totalAtualizados = 0;
            $totalNovos = 0;
            $pagina = 1;
            $totalPaginas = 1;

            do {
                $this->info("Processando página {$pagina} de {$totalPaginas}...");

                // Busca departamentos da API Omie
                $response = $this->omieService->makeRequest(
                    'geral/departamentos/',
                    'ListarDepartamentos',
                    [
                        'pagina' => $pagina,
                        'registros_por_pagina' => 200
                    ]
                );

                if (empty($response['departamentos'])) {
                    $this->warn('Nenhum departamento encontrado na página ' . $pagina);
                    break;
                }

                $totalPaginas = $response['total_de_paginas'] ?? 1;
                $departamentos = $response['departamentos'];

                $this->info('Encontrados ' . count($departamentos) . ' departamentos na página ' . $pagina);

                // Processa cada departamento
                foreach ($departamentos as $departamento) {
                    $resultado = $this->processarDepartamento($departamento);
                    
                    if ($resultado['novo']) {
                        $totalNovos++;
                    } elseif ($resultado['atualizado']) {
                        $totalAtualizados++;
                    }
                    
                    $totalSincronizados++;
                }

                $pagina++;
            } while ($pagina <= $totalPaginas);

            $this->info("\n=== Sincronização concluída ===");
            $this->info("Total processados: {$totalSincronizados}");
            $this->info("Novos: {$totalNovos}");
            $this->info("Atualizados: {$totalAtualizados}");

            Log::info('Sincronização de centros de custo Omie concluída', [
                'total_processados' => $totalSincronizados,
                'novos' => $totalNovos,
                'atualizados' => $totalAtualizados
            ]);

        } catch (\Exception $e) {
            $this->error('Erro durante a sincronização: ' . $e->getMessage());
            Log::error('Erro na sincronização de centros de custo Omie', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }

    /**
     * Processa um departamento individual da API
     */
    private function processarDepartamento(array $departamento): array
    {
        $omieId = $departamento['codigo'];
        $codigo = $departamento['descricao']; // descricao vira código no sistema
        $estrutura = $departamento['estrutura'] ?? null;
        $inativoOmie = $departamento['inativo'] ?? 'N'; // Mantém o valor original 'S' ou 'N'
        $inativo = $inativoOmie === 'S'; // Boolean para lógica interna

        // Busca centro de custo existente pelo omie_codigo
        $centroCusto = CentroCusto::where('omie_codigo', $omieId)->first();

        $dados = [
            'omie_codigo' => $omieId,
            'codigo' => $codigo,
            'omie_estrutura' => $estrutura,
            'omie_inativo' => $inativoOmie, // Salva 'S' ou 'N' como esperado pelo modelo
            'sincronizado_em' => now(),
            'active' => !$inativo // Se não está inativo na Omie, está ativo no sistema
        ];

        $novo = false;
        $atualizado = false;

        if ($centroCusto) {
            // Atualiza centro de custo existente
            $dadosAnteriores = $centroCusto->only(['codigo', 'omie_estrutura', 'omie_inativo', 'active']);
            
            $centroCusto->update($dados);
            
            // Verifica se houve mudanças significativas
            $dadosNovos = $centroCusto->only(['codigo', 'omie_estrutura', 'omie_inativo', 'active']);
            $atualizado = $dadosAnteriores !== $dadosNovos;
            
            if ($atualizado) {
                $this->line("  ✓ Atualizado: {$codigo} (ID Omie: {$omieId})");
            }
        } else {
            // Cria novo centro de custo
            $dados['name'] = $codigo; // Nome inicial igual ao código
            $dados['description'] = "Centro de custo importado da Omie - Estrutura: {$estrutura}";
            
            CentroCusto::create($dados);
            $novo = true;
            
            $this->line("  + Novo: {$codigo} (ID Omie: {$omieId})");
        }

        return [
            'novo' => $novo,
            'atualizado' => $atualizado
        ];
    }
}
