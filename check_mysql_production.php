<?php
/**
 * Script para verificar diretamente no MySQL de produção
 * Servidor: 93.127.210.89
 * Usuário: forge
 * Problema: Verificar coluna id_protocolo na tabela orcamentos
 */

echo "=== VERIFICAÇÃO DIRETA NO MYSQL DE PRODUÇÃO ===\n";
echo "Servidor: 93.127.210.89\n";
echo "Usuário: forge\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n\n";

// Configurações de conexão
$host = '93.127.210.89';
$username = 'forge';
$password = '51D3r41!du57';
$database = 'forge'; // Assumindo que o banco é 'forge', pode ser 'obm' também

try {
    echo "1. TENTANDO CONECTAR AO MYSQL...\n";
    
    // Tentar diferentes nomes de banco de dados
    $databases_to_try = ['forge', 'obm', 'obmsistem'];
    $connection = null;
    $connected_db = null;
    
    foreach ($databases_to_try as $db_name) {
        try {
            echo "   Tentando conectar ao banco: {$db_name}\n";
            $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";
            $connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 10
            ]);
            $connected_db = $db_name;
            echo "   ✅ Conectado com sucesso ao banco: {$db_name}\n";
            break;
        } catch (PDOException $e) {
            echo "   ❌ Falha ao conectar ao banco {$db_name}: " . $e->getMessage() . "\n";
        }
    }
    
    if (!$connection) {
        throw new Exception("Não foi possível conectar a nenhum banco de dados");
    }
    
    echo "\n2. LISTANDO TODOS OS BANCOS DE DADOS DISPONÍVEIS...\n";
    $stmt = $connection->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($databases as $db) {
        echo "   - {$db}\n";
    }
    
    echo "\n3. VERIFICANDO TABELAS NO BANCO '{$connected_db}'...\n";
    $stmt = $connection->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $orcamentos_exists = false;
    foreach ($tables as $table) {
        if ($table === 'orcamentos') {
            $orcamentos_exists = true;
            echo "   ✅ Tabela 'orcamentos' encontrada\n";
        }
    }
    
    if (!$orcamentos_exists) {
        echo "   ❌ Tabela 'orcamentos' NÃO encontrada\n";
        echo "   Tabelas disponíveis:\n";
        foreach ($tables as $table) {
            echo "     - {$table}\n";
        }
    } else {
        echo "\n4. VERIFICANDO ESTRUTURA DA TABELA 'orcamentos'...\n";
        $stmt = $connection->query("DESCRIBE orcamentos");
        $columns = $stmt->fetchAll();
        
        $id_protocolo_exists = false;
        echo "   Colunas da tabela 'orcamentos':\n";
        foreach ($columns as $column) {
            $column_name = $column['Field'];
            $column_type = $column['Type'];
            $is_null = $column['Null'];
            $default = $column['Default'];
            
            if ($column_name === 'id_protocolo') {
                $id_protocolo_exists = true;
                echo "   ✅ {$column_name} ({$column_type}) - NULL: {$is_null} - Default: {$default}\n";
            } else {
                echo "     {$column_name} ({$column_type})\n";
            }
        }
        
        if (!$id_protocolo_exists) {
            echo "   ❌ Coluna 'id_protocolo' NÃO encontrada na tabela 'orcamentos'\n";
        }
        
        echo "\n5. VERIFICANDO REGISTROS NA TABELA 'orcamentos'...\n";
        $stmt = $connection->query("SELECT COUNT(*) as total FROM orcamentos");
        $result = $stmt->fetch();
        echo "   Total de registros: {$result['total']}\n";
        
        if ($result['total'] > 0) {
            echo "\n6. MOSTRANDO PRIMEIROS 3 REGISTROS...\n";
            $stmt = $connection->query("SELECT * FROM orcamentos LIMIT 3");
            $records = $stmt->fetchAll();
            foreach ($records as $i => $record) {
                echo "   Registro " . ($i + 1) . ":\n";
                foreach ($record as $field => $value) {
                    echo "     {$field}: {$value}\n";
                }
                echo "\n";
            }
        }
    }
    
    echo "\n7. VERIFICANDO TABELA DE MIGRAÇÕES...\n";
    $stmt = $connection->query("SHOW TABLES LIKE 'migrations'");
    $migrations_table = $stmt->fetch();
    
    if ($migrations_table) {
        echo "   ✅ Tabela 'migrations' encontrada\n";
        
        // Verificar se a migração id_protocolo foi executada
        $stmt = $connection->prepare("SELECT * FROM migrations WHERE migration LIKE '%id_protocolo%'");
        $stmt->execute();
        $migration_records = $stmt->fetchAll();
        
        if ($migration_records) {
            echo "   ✅ Migração id_protocolo encontrada:\n";
            foreach ($migration_records as $migration) {
                echo "     - {$migration['migration']} (batch: {$migration['batch']})\n";
            }
        } else {
            echo "   ❌ Migração id_protocolo NÃO encontrada\n";
        }
        
        // Mostrar últimas 5 migrações executadas
        echo "\n   Últimas 5 migrações executadas:\n";
        $stmt = $connection->query("SELECT migration, batch FROM migrations ORDER BY batch DESC, id DESC LIMIT 5");
        $recent_migrations = $stmt->fetchAll();
        foreach ($recent_migrations as $migration) {
            echo "     - {$migration['migration']} (batch: {$migration['batch']})\n";
        }
    } else {
        echo "   ❌ Tabela 'migrations' NÃO encontrada\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "\nDetalhes do erro:\n";
    echo "- Verifique se o servidor MySQL está acessível\n";
    echo "- Verifique se as credenciais estão corretas\n";
    echo "- Verifique se o firewall permite conexões na porta 3306\n";
}

echo "\n=== COMANDOS PARA CORREÇÃO MANUAL ===\n";
echo "Se a coluna id_protocolo não existir, execute no servidor:\n\n";
echo "# Conectar ao servidor:\n";
echo "ssh root@93.127.210.89\n\n";
echo "# Navegar para o diretório da aplicação:\n";
echo "cd /home/forge/default\n\n";
echo "# Executar a migração específica:\n";
echo "php artisan migrate --path=database/migrations/2025_01_27_000002_add_id_protocolo_to_orcamentos_table.php\n\n";
echo "# Ou adicionar a coluna manualmente no MySQL:\n";
echo "mysql -u forge -p\n";
echo "USE {$connected_db};\n";
echo "ALTER TABLE orcamentos ADD COLUMN id_protocolo VARCHAR(255) NULL;\n";
echo "INSERT INTO migrations (migration, batch) VALUES ('2025_01_27_000002_add_id_protocolo_to_orcamentos_table', (SELECT MAX(batch) + 1 FROM migrations m));\n";

echo "\n=== FIM DA VERIFICAÇÃO ===\n";
?>