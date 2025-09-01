<?php

require_once 'vendor/autoload.php';

// Carregar o ambiente Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Orcamento;
use App\Models\OrcamentoAumentoKm;

echo "=== VERIFICANDO DADOS DE AUMENTO KM ===\n";

try {
    // Buscar orçamentos de aumento KM
    $orcamentos = Orcamento::where('tipo_orcamento', 'aumento_km')->get();
    
    echo "Orçamentos de Aumento KM encontrados: " . $orcamentos->count() . "\n\n";
    
    foreach ($orcamentos as $orcamento) {
        echo "--- Orçamento ID: {$orcamento->id} ---\n";
        echo "Tipo: {$orcamento->tipo_orcamento}\n";
        echo "Nome da Rota: {$orcamento->nome_rota}\n";
        echo "Valor Total: {$orcamento->valor_total}\n";
        
        // Buscar dados específicos de aumento KM
        $aumentoKm = OrcamentoAumentoKm::where('orcamento_id', $orcamento->id)->first();
        
        if ($aumentoKm) {
            echo "✓ Dados de Aumento KM encontrados:\n";
            echo "  - KM Dia: {$aumentoKm->km_dia}\n";
            echo "  - Qtd Dias: {$aumentoKm->qtd_dias}\n";
            echo "  - Combustível KM/L: {$aumentoKm->combustivel_km_litro}\n";
            echo "  - Valor Combustível: {$aumentoKm->valor_combustivel}\n";
            echo "  - Hora Extra: {$aumentoKm->hora_extra}\n";
            echo "  - Pedágio: {$aumentoKm->pedagio}\n";
            echo "  - Lucro %: {$aumentoKm->lucro_percentual}\n";
            echo "  - Valor Lucro: {$aumentoKm->valor_lucro}\n";
            echo "  - Impostos %: {$aumentoKm->impostos_percentual}\n";
            echo "  - Valor Impostos: {$aumentoKm->valor_impostos}\n";
            echo "  - Valor Total: {$aumentoKm->valor_total}\n";
        } else {
            echo "✗ Nenhum dado de Aumento KM encontrado para este orçamento!\n";
        }
        
        echo "\n";
    }
    
    // Verificar se há registros órfãos na tabela orcamento_aumento_km
    echo "=== VERIFICANDO REGISTROS ÓRFÃOS ===\n";
    $aumentoKmRecords = OrcamentoAumentoKm::all();
    echo "Total de registros em orcamento_aumento_km: " . $aumentoKmRecords->count() . "\n";
    
    foreach ($aumentoKmRecords as $record) {
        $orcamento = Orcamento::find($record->orcamento_id);
        if (!$orcamento) {
            echo "✗ Registro órfão encontrado - ID: {$record->id}, orcamento_id: {$record->orcamento_id}\n";
        } else {
            echo "✓ Registro OK - ID: {$record->id}, vinculado ao orçamento: {$record->orcamento_id}\n";
        }
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}

echo "\n=== FIM DA VERIFICAÇÃO ===\n";