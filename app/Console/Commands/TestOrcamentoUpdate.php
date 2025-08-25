<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orcamento;

class TestOrcamentoUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orcamentos:test-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar atualização automática do valor_final';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testando atualização automática do valor_final...');
        
        $orcamento = Orcamento::first();
        
        if (!$orcamento) {
            $this->error('Nenhum orçamento encontrado!');
            return 1;
        }
        
        $this->info("Orçamento ID: {$orcamento->id}");
        $this->info("Antes da atualização:");
        $this->info("- valor_total: {$orcamento->valor_total}");
        $this->info("- valor_impostos: {$orcamento->valor_impostos}");
        $this->info("- valor_final: {$orcamento->valor_final}");
        
        // Atualizar valores
        $orcamento->valor_total = 1500.00;
        $orcamento->valor_impostos = 150.00;
        $orcamento->save();
        
        // Recarregar do banco
        $orcamento->refresh();
        
        $this->info("\nDepois da atualização:");
        $this->info("- valor_total: {$orcamento->valor_total}");
        $this->info("- valor_impostos: {$orcamento->valor_impostos}");
        $this->info("- valor_final: {$orcamento->valor_final}");
        
        $expectedFinal = $orcamento->valor_total + $orcamento->valor_impostos;
        
        if ($orcamento->valor_final == $expectedFinal) {
            $this->info("\n✅ Sucesso! O valor_final foi atualizado automaticamente.");
        } else {
            $this->error("\n❌ Erro! O valor_final não foi atualizado corretamente.");
            $this->error("Esperado: {$expectedFinal}, Atual: {$orcamento->valor_final}");
        }
        
        return 0;
    }
}