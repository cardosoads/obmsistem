<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orcamento;

class DiagnoseOrcamentosStatus extends Command
{
    protected $signature = 'orcamentos:diagnose-status';
    protected $description = 'Diagnostica problemas de status nos orçamentos';

    public function handle()
    {
        $this->info('Iniciando diagnóstico de status dos orçamentos...');
        
        $orcamentos = Orcamento::all();
        
        $this->info("Encontrados {$orcamentos->count()} orçamentos para análise.");
        
        $statusCounts = [];
        $invalidStatus = [];
        
        foreach ($orcamentos as $orcamento) {
            $status = $orcamento->status;
            
            // Contar status
            if (!isset($statusCounts[$status])) {
                $statusCounts[$status] = 0;
            }
            $statusCounts[$status]++;
            
            // Verificar se o status é válido
            $validStatuses = ['rascunho', 'enviado', 'aprovado', 'rejeitado', 'cancelado'];
            if (!in_array($status, $validStatuses)) {
                $invalidStatus[] = [
                    'id' => $orcamento->id,
                    'numero' => $orcamento->numero_orcamento,
                    'status' => $status
                ];
            }
            
            $this->line("Orçamento #{$orcamento->numero_orcamento} (ID: {$orcamento->id}) - Status: {$status}");
        }
        
        $this->info("\n" . str_repeat('=', 50));
        $this->info('RESUMO DE STATUS:');
        
        foreach ($statusCounts as $status => $count) {
            $this->info("  {$status}: {$count} orçamento(s)");
        }
        
        if (!empty($invalidStatus)) {
            $this->warn("\nSTATUS INVÁLIDOS ENCONTRADOS:");
            foreach ($invalidStatus as $invalid) {
                $this->warn("  - Orçamento #{$invalid['numero']} (ID: {$invalid['id']}) tem status inválido: '{$invalid['status']}'");
            }
        } else {
            $this->info("\n✓ Todos os status estão válidos!");
        }
        
        return 0;
    }
}