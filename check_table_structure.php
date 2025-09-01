<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== ESTRUTURA DA TABELA ORCAMENTOS ===\n";
    
    // Verificar se a tabela existe
    $tableExists = DB::select("SHOW TABLES LIKE 'orcamentos'");
    
    if (empty($tableExists)) {
        echo "❌ Tabela 'orcamentos' não existe!\n";
        exit(1);
    }
    
    echo "✅ Tabela 'orcamentos' existe\n\n";
    
    // Obter estrutura da tabela
    $columns = DB::select('DESCRIBE orcamentos');
    
    echo "Colunas encontradas:\n";
    echo str_repeat('-', 80) . "\n";
    printf("%-25s %-20s %-10s %-15s\n", 'CAMPO', 'TIPO', 'NULL', 'DEFAULT');
    echo str_repeat('-', 80) . "\n";
    
    $idProtocoloExists = false;
    
    foreach ($columns as $column) {
        printf("%-25s %-20s %-10s %-15s\n", 
            $column->Field, 
            $column->Type, 
            $column->Null, 
            $column->Default ?? 'NULL'
        );
        
        if ($column->Field === 'id_protocolo') {
            $idProtocoloExists = true;
        }
    }
    
    echo str_repeat('-', 80) . "\n\n";
    
    if ($idProtocoloExists) {
        echo "✅ Coluna 'id_protocolo' EXISTE na tabela\n";
    } else {
        echo "❌ Coluna 'id_protocolo' NÃO EXISTE na tabela\n";
        echo "\n🔧 AÇÃO NECESSÁRIA: Executar migração para adicionar a coluna\n";
    }
    
    // Verificar status da migração específica
    echo "\n=== STATUS DA MIGRAÇÃO ===\n";
    $migration = DB::table('migrations')
        ->where('migration', 'like', '%add_id_protocolo_to_orcamentos_table%')
        ->first();
    
    if ($migration) {
        echo "✅ Migração 'add_id_protocolo_to_orcamentos_table' está registrada como executada\n";
        echo "   Batch: {$migration->batch}\n";
        echo "   Executada em: {$migration->ran_at}\n";
    } else {
        echo "❌ Migração 'add_id_protocolo_to_orcamentos_table' NÃO está registrada\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro ao verificar estrutura da tabela: " . $e->getMessage() . "\n";
    exit(1);
}