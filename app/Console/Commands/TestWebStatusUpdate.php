<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orcamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\OrcamentoController;

class TestWebStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orcamentos:test-web-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar atualização de status via controller web';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testando atualização de status via controller web...');
        
        $orcamento = Orcamento::first();
        
        if (!$orcamento) {
            $this->error('Nenhum orçamento encontrado!');
            return 1;
        }
        
        $this->info("Orçamento ID: {$orcamento->id}");
        $this->info("Status atual: {$orcamento->status}");
        
        // Simular requisição web
        $controller = new OrcamentoController();
        
        // Criar request simulado
        $request = new Request();
        $request->merge(['status' => 'enviado']);
        
        try {
            $response = $controller->updateStatus($request, $orcamento);
            $responseData = $response->getData(true);
            
            $this->info("Resposta do controller:");
            $this->info("- Success: " . ($responseData['success'] ? 'true' : 'false'));
            $this->info("- Message: " . $responseData['message']);
            
            // Recarregar orçamento
            $orcamento->refresh();
            $this->info("Status após atualização: {$orcamento->status}");
            
            if ($orcamento->status === 'enviado') {
                $this->info("\n✅ Sucesso! Status atualizado via controller.");
            } else {
                $this->error("\n❌ Erro! Status não foi atualizado.");
            }
            
        } catch (\Exception $e) {
            $this->error("Erro ao testar controller: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
        }
        
        return 0;
    }
}