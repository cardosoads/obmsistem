<?php

// Este script deve ser executado via: php artisan tinker --execute="require 'debug_search.php';"
// Ou criar um comando artisan

use App\Services\OmieService;

$omieService = app(OmieService::class);

echo "=== DEBUG DA BUSCA ===\n\n";

// Listar todos os fornecedores da primeira página
$response = $omieService->listarFornecedores([
    'pagina' => 1,
    'registros_por_pagina' => 100
]);

if ($response['success']) {
    $fornecedores = $response['data']['clientes_cadastro'] ?? [];
    
    echo "Total de fornecedores encontrados: " . count($fornecedores) . "\n\n";
    
    // Procurar por BRACARE
    $encontrados = [];
    foreach ($fornecedores as $fornecedor) {
        $razaoSocial = $fornecedor['razao_social'] ?? '';
        $nomeFantasia = $fornecedor['nome_fantasia'] ?? '';
        
        if (str_contains(strtolower($razaoSocial), 'bracare') || 
            str_contains(strtolower($nomeFantasia), 'bracare')) {
            $encontrados[] = $fornecedor;
        }
    }
    
    echo "Fornecedores com 'BRACARE' encontrados: " . count($encontrados) . "\n";
    
    foreach ($encontrados as $fornecedor) {
        echo "- Código: " . ($fornecedor['codigo_cliente_omie'] ?? 'N/A') . "\n";
        echo "  Razão Social: " . ($fornecedor['razao_social'] ?? 'N/A') . "\n";
        echo "  Nome Fantasia: " . ($fornecedor['nome_fantasia'] ?? 'N/A') . "\n\n";
    }
    
    // Verificar se o código específico está na lista
    echo "\n=== VERIFICANDO CÓDIGO 10373465046 ===\n";
    $codigoEncontrado = false;
    foreach ($fornecedores as $fornecedor) {
        if (($fornecedor['codigo_cliente_omie'] ?? '') == '10373465046') {
            $codigoEncontrado = true;
            echo "✓ Código 10373465046 ENCONTRADO na listagem\n";
            echo "  Razão Social: " . ($fornecedor['razao_social'] ?? 'N/A') . "\n";
            echo "  Nome Fantasia: " . ($fornecedor['nome_fantasia'] ?? 'N/A') . "\n";
            break;
        }
    }
    
    if (!$codigoEncontrado) {
        echo "✗ Código 10373465046 NÃO encontrado na listagem\n";
        echo "Isso significa que ele está em uma página posterior\n";
    }
    
} else {
    echo "Erro ao listar fornecedores: " . ($response['message'] ?? 'Erro desconhecido') . "\n";
}

echo "\n=== DEBUG CONCLUÍDO ===\n";