<?php
/**
 * Script para verificar o status da aplicação em produção
 * Servidor: 93.127.210.89
 * Problema: Coluna id_protocolo não encontrada na tabela orcamentos
 */

echo "=== VERIFICAÇÃO DO STATUS DA APLICAÇÃO EM PRODUÇÃO ===\n";
echo "Servidor: 93.127.210.89\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n\n";

// Verificar conectividade básica
echo "1. TESTE DE CONECTIVIDADE\n";
echo "Testando ping para 93.127.210.89...\n";
$ping_result = shell_exec('ping 93.127.210.89 -n 2');
if (strpos($ping_result, 'TTL=') !== false) {
    echo "✅ Servidor respondendo ao ping\n";
} else {
    echo "❌ Servidor não responde ao ping\n";
}
echo "\n";

// Verificar se o site está acessível
echo "2. TESTE DE ACESSO HTTP\n";
$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'method' => 'GET',
        'header' => 'User-Agent: Production-Check-Script'
    ]
]);

$urls_to_check = [
    'http://93.127.210.89',
    'https://93.127.210.89'
];

foreach ($urls_to_check as $url) {
    echo "Testando acesso a {$url}...\n";
    $response = @file_get_contents($url, false, $context);
    if ($response !== false) {
        echo "✅ Site acessível via {$url}\n";
        $response_length = strlen($response);
        echo "   Tamanho da resposta: {$response_length} bytes\n";
        
        // Verificar se há erros Laravel na resposta
        if (strpos($response, 'Whoops') !== false || strpos($response, 'Laravel') !== false) {
            echo "⚠️  Possível erro Laravel detectado na resposta\n";
        }
    } else {
        echo "❌ Site não acessível via {$url}\n";
    }
}
echo "\n";

// Instruções para verificação manual
echo "3. VERIFICAÇÕES MANUAIS NECESSÁRIAS\n";
echo "Para resolver o problema da coluna id_protocolo, execute os seguintes comandos no servidor:\n\n";

echo "# Conectar ao servidor:\n";
echo "ssh root@93.127.210.89\n\n";

echo "# Navegar para o diretório da aplicação:\n";
echo "cd /home/forge/default\n\n";

echo "# Verificar status das migrações:\n";
echo "php artisan migrate:status\n\n";

echo "# Verificar se a tabela orcamentos existe:\n";
echo "php artisan tinker\n";
echo "Schema::hasTable('orcamentos')\n";
echo "Schema::hasColumn('orcamentos', 'id_protocolo')\n";
echo "exit\n\n";

echo "# Se a coluna não existir, executar a migração específica:\n";
echo "php artisan migrate --path=database/migrations/2025_01_27_000002_add_id_protocolo_to_orcamentos_table.php\n\n";

echo "# Verificar logs de erro:\n";
echo "tail -f storage/logs/laravel.log\n\n";

echo "# Limpar cache se necessário:\n";
echo "php artisan config:clear\n";
echo "php artisan cache:clear\n";
echo "php artisan view:clear\n\n";

echo "4. DIAGNÓSTICO DO PROBLEMA\n";
echo "Baseado no histórico, o erro 'Column not found: 1054 Unknown column id_protocolo' indica que:\n";
echo "- A migração add_id_protocolo_to_orcamentos_table pode não ter sido executada corretamente\n";
echo "- A tabela orcamentos pode não existir em produção\n";
echo "- Pode haver inconsistência entre o código e o banco de dados\n\n";

echo "5. ARQUIVOS DE CORREÇÃO DISPONÍVEIS\n";
echo "- fix_id_protocolo_production.php: Script para adicionar a coluna manualmente\n";
echo "- FORGE_MIGRATION_FIX.md: Documentação completa do problema\n";
echo "- check_table_structure.php: Script para verificar estrutura da tabela\n\n";

echo "=== FIM DA VERIFICAÇÃO ===\n";
?>