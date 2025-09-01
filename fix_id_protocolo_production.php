<?php

/**
 * Script de correção para adicionar a coluna id_protocolo em produção
 * Execute este script no Forge se as migrações não funcionarem
 */

require_once __DIR__ . '/vendor/autoload.php';

// Carrega as configurações do Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

try {
    echo "=== CORREÇÃO DA COLUNA id_protocolo EM PRODUÇÃO ===".PHP_EOL;
    
    // Verifica se a tabela orcamentos existe
    if (!Schema::hasTable('orcamentos')) {
        echo "❌ ERRO CRÍTICO: Tabela 'orcamentos' não existe!".PHP_EOL;
        echo "📋 Execute primeiro: php artisan migrate".PHP_EOL;
        exit(1);
    }
    
    echo "✅ Tabela 'orcamentos' existe".PHP_EOL;
    
    // Verifica se a coluna id_protocolo já existe
    if (Schema::hasColumn('orcamentos', 'id_protocolo')) {
        echo "✅ Coluna 'id_protocolo' já existe. Nenhuma ação necessária.".PHP_EOL;
        exit(0);
    }
    
    echo "⚠️  Coluna 'id_protocolo' não existe. Adicionando...".PHP_EOL;
    
    // Adiciona a coluna manualmente
    Schema::table('orcamentos', function (Blueprint $table) {
        $table->string('id_protocolo')->nullable()
              ->comment('ID de Protocolo digitado pelo usuário')
              ->after('centro_custo_id');
    });
    
    echo "✅ Coluna 'id_protocolo' adicionada com sucesso!".PHP_EOL;
    
    // Verifica se foi adicionada corretamente
    if (Schema::hasColumn('orcamentos', 'id_protocolo')) {
        echo "✅ Verificação: Coluna existe e está funcionando".PHP_EOL;
        
        // Mostra informações da coluna
        $columnInfo = DB::select("SHOW COLUMNS FROM orcamentos WHERE Field = 'id_protocolo'")[0];
        echo "📋 Tipo: {$columnInfo->Type}".PHP_EOL;
        echo "📋 NULL: {$columnInfo->Null}".PHP_EOL;
        echo "📋 Padrão: {$columnInfo->Default}".PHP_EOL;
        
        // Marca a migração como executada para evitar conflitos
        $migrationName = '2025_01_27_000002_add_id_protocolo_to_orcamentos_table';
        $exists = DB::table('migrations')->where('migration', $migrationName)->exists();
        
        if (!$exists) {
            DB::table('migrations')->insert([
                'migration' => $migrationName,
                'batch' => DB::table('migrations')->max('batch') + 1
            ]);
            echo "✅ Migração marcada como executada no banco".PHP_EOL;
        }
        
        echo "\n🎉 CORREÇÃO CONCLUÍDA COM SUCESSO!".PHP_EOL;
        echo "📋 A aplicação agora deve funcionar normalmente.".PHP_EOL;
        
    } else {
        echo "❌ ERRO: Falha ao adicionar a coluna".PHP_EOL;
        exit(1);
    }
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . PHP_EOL;
    echo "📋 Stack trace: " . $e->getTraceAsString() . PHP_EOL;
    exit(1);
}