<?php

require_once 'vendor/autoload.php';

// Carregar variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    // Conectar ao banco de dados
    $pdo = new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Verificando base RJO especificamente:\n";
    
    // Buscar base com sigla RJO
    $stmt = $pdo->prepare("SELECT * FROM bases WHERE sigla = 'RJO' ORDER BY created_at DESC");
    $stmt->execute();
    $baseRJO = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($baseRJO) {
        echo "Base RJO encontrada:\n";
        foreach ($baseRJO as $base) {
            echo "- ID: {$base['id']}, Nome: {$base['name']}, UF: {$base['uf']}, Sigla: {$base['sigla']}, Supervisor: {$base['supervisor']}, Criado em: {$base['created_at']}\n";
        }
    } else {
        echo "Base com sigla RJO não foi encontrada.\n";
    }
    
    // Verificar as últimas 5 bases criadas
    echo "\nÚltimas 5 bases criadas:\n";
    $stmt = $pdo->prepare("SELECT * FROM bases ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $ultimasBases = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($ultimasBases as $base) {
        echo "- ID: {$base['id']}, Nome: {$base['name']}, UF: {$base['uf']}, Sigla: {$base['sigla']}, Supervisor: {$base['supervisor']}, Criado em: {$base['created_at']}\n";
    }
    
    // Verificar se há bases com nome "Rio de Janeiro"
    echo "\nBases com nome 'Rio de Janeiro':\n";
    $stmt = $pdo->prepare("SELECT * FROM bases WHERE name = 'Rio de Janeiro' ORDER BY created_at DESC");
    $stmt->execute();
    $basesRJ = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($basesRJ) {
        foreach ($basesRJ as $base) {
            echo "- ID: {$base['id']}, Nome: {$base['name']}, UF: {$base['uf']}, Sigla: {$base['sigla']}, Supervisor: {$base['supervisor']}, Criado em: {$base['created_at']}\n";
        }
    } else {
        echo "Nenhuma base com nome 'Rio de Janeiro' encontrada.\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}