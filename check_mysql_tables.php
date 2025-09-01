<?php

require_once 'vendor/autoload.php';

// Carregar o ambiente Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== VERIFICANDO TABELAS NO MYSQL ===\n";

try {
    // Testar conexão
    $pdo = DB::connection()->getPdo();
    echo "Conexão OK com banco: " . DB::connection()->getDatabaseName() . "\n\n";
    
    // Listar tabelas
    $tables = DB::select('SHOW TABLES');
    
    if (empty($tables)) {
        echo "Nenhuma tabela encontrada no banco!\n";
    } else {
        echo "Tabelas encontradas:\n";
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            echo "- {$tableName}\n";
        }
    }
    
    // Verificar especificamente as tabelas de orçamento
    echo "\n=== VERIFICANDO TABELAS DE ORÇAMENTO ===\n";
    $orcamentoTables = ['orcamentos', 'orcamento_aumento_km', 'orcamento_prestador', 'orcamento_proprio_nova_rota'];
    
    foreach ($orcamentoTables as $tableName) {
        try {
            $result = DB::select("SHOW TABLES LIKE '{$tableName}'");
            if (!empty($result)) {
                echo "✓ Tabela '{$tableName}' existe\n";
                
                // Contar registros
                $count = DB::table($tableName)->count();
                echo "  - Registros: {$count}\n";
            } else {
                echo "✗ Tabela '{$tableName}' NÃO existe\n";
            }
        } catch (Exception $e) {
            echo "✗ Erro ao verificar '{$tableName}': " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Erro na conexão: " . $e->getMessage() . "\n";
}

echo "\n=== FIM DA VERIFICAÇÃO ===\n";