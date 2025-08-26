<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Verificar estrutura da tabela
$pdo = DB::connection()->getPdo();
$stmt = $pdo->query("DESCRIBE bases");
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Estrutura da tabela bases:\n";
foreach ($columns as $column) {
    echo "- {$column['Field']} ({$column['Type']}) - {$column['Null']} - {$column['Default']}\n";
}

echo "\n\nTentando criar uma base de teste:\n";

try {
    $base = new App\Models\Base();
    $base->uf = 'SP';
    $base->name = 'São Paulo';
    $base->regional = 'Sudeste';
    $base->sigla = 'SPL';
    $base->supervisor = 'João Silva';
    $base->active = true;
    
    $result = $base->save();
    
    if ($result) {
        echo "Base salva com sucesso! ID: {$base->id}\n";
    } else {
        echo "Falha ao salvar a base.\n";
    }
    
} catch (Exception $e) {
    echo "Erro ao salvar: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}