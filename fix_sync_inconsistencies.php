<?php

require_once __DIR__ . '/vendor/autoload.php';

// Carregar configuração do Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CentroCusto;
use Illuminate\Support\Facades\DB;

echo "Corrigindo inconsistências na sincronização com a API Omie..." . PHP_EOL . PHP_EOL;

// Buscar registros com inconsistências
$registrosSincronizados = CentroCusto::whereNotNull('omie_codigo')->get();

$correcoes = [
    'ativo_omie_inativo_local' => [],
    'inativo_omie_ativo_local' => []
];

foreach ($registrosSincronizados as $centro) {
    // Problema 1: omie_inativo='N' mas active=false
    if ($centro->omie_inativo === 'N' && !$centro->active) {
        $correcoes['ativo_omie_inativo_local'][] = $centro;
    }
    
    // Problema 2: omie_inativo='S' mas active=true
    if ($centro->omie_inativo === 'S' && $centro->active) {
        $correcoes['inativo_omie_ativo_local'][] = $centro;
    }
}

echo "Registros a corrigir:" . PHP_EOL;
echo "- Ativos na Omie mas inativos localmente: " . count($correcoes['ativo_omie_inativo_local']) . PHP_EOL;
echo "- Inativos na Omie mas ativos localmente: " . count($correcoes['inativo_omie_ativo_local']) . PHP_EOL;
echo PHP_EOL;

if (empty($correcoes['ativo_omie_inativo_local']) && empty($correcoes['inativo_omie_ativo_local'])) {
    echo "✓ Nenhuma inconsistência encontrada para corrigir!" . PHP_EOL;
    exit(0);
}

// Confirmar correção
echo "Esta operação irá:" . PHP_EOL;
echo "1. Ativar localmente os registros que estão ativos na Omie (omie_inativo='N')" . PHP_EOL;
echo "2. Desativar localmente os registros que estão inativos na Omie (omie_inativo='S')" . PHP_EOL;
echo PHP_EOL;

// Executar correções
DB::beginTransaction();

try {
    $totalCorrigidos = 0;
    
    // Corrigir registros ativos na Omie mas inativos localmente
    if (!empty($correcoes['ativo_omie_inativo_local'])) {
        echo "Ativando registros que estão ativos na Omie..." . PHP_EOL;
        
        foreach ($correcoes['ativo_omie_inativo_local'] as $centro) {
            $centro->update(['active' => true]);
            echo "  ✓ ID {$centro->id} - {$centro->codigo} ativado" . PHP_EOL;
            $totalCorrigidos++;
        }
    }
    
    // Corrigir registros inativos na Omie mas ativos localmente
    if (!empty($correcoes['inativo_omie_ativo_local'])) {
        echo "Desativando registros que estão inativos na Omie..." . PHP_EOL;
        
        foreach ($correcoes['inativo_omie_ativo_local'] as $centro) {
            $centro->update(['active' => false]);
            echo "  ✓ ID {$centro->id} - {$centro->codigo} desativado" . PHP_EOL;
            $totalCorrigidos++;
        }
    }
    
    DB::commit();
    
    echo PHP_EOL . "✅ Correção concluída com sucesso!" . PHP_EOL;
    echo "Total de registros corrigidos: {$totalCorrigidos}" . PHP_EOL;
    
    // Verificação pós-correção
    echo PHP_EOL . "Verificando resultado..." . PHP_EOL;
    
    $registrosVerificacao = CentroCusto::whereNotNull('omie_codigo')->get();
    $inconsistenciasRestantes = 0;
    
    foreach ($registrosVerificacao as $centro) {
        if (($centro->omie_inativo === 'N' && !$centro->active) || 
            ($centro->omie_inativo === 'S' && $centro->active)) {
            $inconsistenciasRestantes++;
        }
    }
    
    if ($inconsistenciasRestantes === 0) {
        echo "✅ Todas as inconsistências foram corrigidas!" . PHP_EOL;
    } else {
        echo "⚠️  Ainda existem {$inconsistenciasRestantes} inconsistências." . PHP_EOL;
    }
    
} catch (\Exception $e) {
    DB::rollback();
    echo "❌ Erro durante a correção: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

echo PHP_EOL . "Operação finalizada." . PHP_EOL;