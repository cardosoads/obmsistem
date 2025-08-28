<?php

require_once __DIR__ . '/vendor/autoload.php';

// Carregar configuração do Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CentroCusto;

echo "Investigando inconsistências na sincronização com a API Omie..." . PHP_EOL . PHP_EOL;

// Buscar todos os registros sincronizados
$registrosSincronizados = CentroCusto::whereNotNull('omie_codigo')->get();

echo "Total de registros sincronizados: " . $registrosSincronizados->count() . PHP_EOL . PHP_EOL;

// Analisar inconsistências
$inconsistencias = [];

foreach ($registrosSincronizados as $centro) {
    $problemas = [];
    
    // Problema 1: omie_inativo='N' mas active=false
    if ($centro->omie_inativo === 'N' && !$centro->active) {
        $problemas[] = 'Ativo na Omie mas inativo localmente';
    }
    
    // Problema 2: omie_inativo='S' mas active=true
    if ($centro->omie_inativo === 'S' && $centro->active) {
        $problemas[] = 'Inativo na Omie mas ativo localmente';
    }
    
    // Problema 3: omie_inativo vazio ou inválido
    if (!in_array($centro->omie_inativo, ['N', 'S', ''])) {
        $problemas[] = 'Status Omie inválido: ' . $centro->omie_inativo;
    }
    
    if (!empty($problemas)) {
        $inconsistencias[] = [
            'id' => $centro->id,
            'codigo' => $centro->codigo,
            'omie_codigo' => $centro->omie_codigo,
            'omie_inativo' => $centro->omie_inativo,
            'active' => $centro->active,
            'problemas' => $problemas
        ];
    }
}

echo "Inconsistências encontradas: " . count($inconsistencias) . PHP_EOL . PHP_EOL;

if (!empty($inconsistencias)) {
    echo "Detalhes das inconsistências:" . PHP_EOL;
    echo str_repeat('-', 80) . PHP_EOL;
    
    foreach ($inconsistencias as $inc) {
        echo "ID: {$inc['id']} | Código: {$inc['codigo']} | Omie: {$inc['omie_codigo']}" . PHP_EOL;
        echo "  omie_inativo: '{$inc['omie_inativo']}' | active: " . ($inc['active'] ? 'true' : 'false') . PHP_EOL;
        echo "  Problemas: " . implode(', ', $inc['problemas']) . PHP_EOL;
        echo str_repeat('-', 40) . PHP_EOL;
    }
    
    // Estatísticas por tipo de problema
    echo PHP_EOL . "Estatísticas por tipo de problema:" . PHP_EOL;
    $tiposProblema = [];
    
    foreach ($inconsistencias as $inc) {
        foreach ($inc['problemas'] as $problema) {
            if (!isset($tiposProblema[$problema])) {
                $tiposProblema[$problema] = 0;
            }
            $tiposProblema[$problema]++;
        }
    }
    
    foreach ($tiposProblema as $tipo => $count) {
        echo "- {$tipo}: {$count} registro(s)" . PHP_EOL;
    }
    
} else {
    echo "✓ Nenhuma inconsistência encontrada!" . PHP_EOL;
}

echo PHP_EOL . "Investigação concluída." . PHP_EOL;