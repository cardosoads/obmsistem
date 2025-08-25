<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orcamento;
use App\Models\OrcamentoPrestador;
use App\Models\OrcamentoAumentoKm;
use App\Models\OrcamentoProprioNovaRota;

class FixOrcamentosValues extends Command
{
    protected $signature = 'orcamentos:fix-values {--dry-run : Execute sem fazer alterações}';
    protected $description = 'Corrige valores zerados nos orçamentos recalculando com base nos dados específicos';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('Iniciando correção de valores dos orçamentos...');
        
        if ($dryRun) {
            $this->warn('MODO DRY-RUN: Nenhuma alteração será salva no banco de dados.');
        }
        
        $orcamentos = Orcamento::with(['orcamentoPrestador', 'orcamentoAumentoKm', 'orcamentoProprioNovaRota'])->get();
        
        $this->info("Encontrados {$orcamentos->count()} orçamentos para análise.");
        
        $fixed = 0;
        $errors = 0;
        
        foreach ($orcamentos as $orcamento) {
            try {
                $this->line("\nAnalisando orçamento #{$orcamento->numero_orcamento} (ID: {$orcamento->id})");
                $this->line("Tipo: {$orcamento->tipo_orcamento}");
                $this->line("Status: {$orcamento->status}");
                $this->line("Valor Total Atual: R$ " . number_format($orcamento->valor_total ?? 0, 2, ',', '.'));
                $this->line("Valor Final Atual: R$ " . number_format($orcamento->valor_final ?? 0, 2, ',', '.'));
                
                $needsUpdate = false;
                $newValorTotal = 0;
                $newValorImpostos = 0;
                $newValorFinal = 0;
                
                switch ($orcamento->tipo_orcamento) {
                    case 'prestador':
                        if ($orcamento->orcamentoPrestador) {
                            $prestador = $orcamento->orcamentoPrestador;
                            $this->line("  - Dados do prestador encontrados");
                            $this->line("  - Valor referência: R$ " . number_format($prestador->valor_referencia ?? 0, 2, ',', '.'));
                            $this->line("  - Qtd dias: {$prestador->qtd_dias}");
                            $this->line("  - Custo fornecedor: R$ " . number_format($prestador->custo_fornecedor ?? 0, 2, ',', '.'));
                            $this->line("  - Valor total prestador: R$ " . number_format($prestador->valor_total ?? 0, 2, ',', '.'));
                            
                            if ($prestador->valor_total > 0) {
                                $newValorTotal = $prestador->valor_total;
                                $newValorImpostos = $prestador->valor_impostos ?? 0;
                                $newValorFinal = $newValorTotal + $newValorImpostos;
                                
                                if ($orcamento->valor_total != $newValorTotal || $orcamento->valor_final != $newValorFinal) {
                                    $needsUpdate = true;
                                }
                            }
                        } else {
                            $this->warn("  - Dados do prestador não encontrados!");
                        }
                        break;
                        
                    case 'aumento_km':
                        if ($orcamento->orcamentoAumentoKm) {
                            $aumentoKm = $orcamento->orcamentoAumentoKm;
                            $this->line("  - Dados do aumento KM encontrados");
                            $this->line("  - Valor total aumento KM: R$ " . number_format($aumentoKm->valor_total ?? 0, 2, ',', '.'));
                            
                            if ($aumentoKm->valor_total > 0) {
                                $newValorTotal = $aumentoKm->valor_total;
                                $newValorImpostos = $aumentoKm->valor_impostos ?? 0;
                                $newValorFinal = $newValorTotal + $newValorImpostos;
                                
                                if ($orcamento->valor_total != $newValorTotal || $orcamento->valor_final != $newValorFinal) {
                                    $needsUpdate = true;
                                }
                            }
                        } else {
                            $this->warn("  - Dados do aumento KM não encontrados!");
                        }
                        break;
                        
                    case 'proprio_nova_rota':
                        if ($orcamento->orcamentoProprioNovaRota) {
                            $proprioRota = $orcamento->orcamentoProprioNovaRota;
                            $this->line("  - Dados da nova rota encontrados");
                            $this->line("  - Valor total nova rota: R$ " . number_format($proprioRota->valor_total ?? 0, 2, ',', '.'));
                            
                            if ($proprioRota->valor_total > 0) {
                                $newValorTotal = $proprioRota->valor_total;
                                $newValorImpostos = $proprioRota->valor_impostos ?? 0;
                                $newValorFinal = $newValorTotal + $newValorImpostos;
                                
                                if ($orcamento->valor_total != $newValorTotal || $orcamento->valor_final != $newValorFinal) {
                                    $needsUpdate = true;
                                }
                            }
                        } else {
                            $this->warn("  - Dados da nova rota não encontrados!");
                        }
                        break;
                }
                
                if ($needsUpdate) {
                    $this->info("  → CORREÇÃO NECESSÁRIA:");
                    $this->info("    Novo valor total: R$ " . number_format($newValorTotal, 2, ',', '.'));
                    $this->info("    Novo valor impostos: R$ " . number_format($newValorImpostos, 2, ',', '.'));
                    $this->info("    Novo valor final: R$ " . number_format($newValorFinal, 2, ',', '.'));
                    
                    if (!$dryRun) {
                        $orcamento->update([
                            'valor_total' => $newValorTotal,
                            'valor_impostos' => $newValorImpostos,
                            'valor_final' => $newValorFinal
                        ]);
                        $this->info("    ✓ Valores atualizados no banco de dados");
                    } else {
                        $this->info("    (DRY-RUN: Alterações não salvas)");
                    }
                    
                    $fixed++;
                } else {
                    $this->info("  ✓ Valores já estão corretos");
                }
                
            } catch (\Exception $e) {
                $this->error("Erro ao processar orçamento {$orcamento->id}: " . $e->getMessage());
                $errors++;
            }
        }
        
        $this->info("\n" . str_repeat('=', 50));
        $this->info("RESUMO DA EXECUÇÃO:");
        $this->info("Total de orçamentos analisados: {$orcamentos->count()}");
        $this->info("Orçamentos que precisavam de correção: {$fixed}");
        $this->info("Erros encontrados: {$errors}");
        
        if ($dryRun && $fixed > 0) {
            $this->warn("\nPara aplicar as correções, execute o comando sem --dry-run:");
            $this->warn("php artisan orcamentos:fix-values");
        }
        
        return 0;
    }
}