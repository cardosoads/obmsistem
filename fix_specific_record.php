<?php

require_once __DIR__ . '/vendor/autoload.php';

// Carregar configuração do Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CentroCusto;

echo "Corrigindo registro com omie_codigo '3344966640'..." . PHP_EOL . PHP_EOL;

$centro = CentroCusto::where('omie_codigo', '3344966640')->first();

if ($centro) {
    echo "✓ Registro encontrado - Status ANTES da correção:" . PHP_EOL;
    echo "Omie Inativo: '" . $centro->omie_inativo . "'" . PHP_EOL;
    echo "Active (campo local): " . ($centro->active ? 'true' : 'false') . PHP_EOL;
    echo "isAtivoOmie(): " . ($centro->isAtivoOmie() ? 'true' : 'false') . PHP_EOL;
    echo PHP_EOL;
    
    // Dados corretos da API Omie
    $dadosOmieCorretos = [
        'codigo' => '3344966640',
        'descricao' => '3BA23',
        'estrutura' => '001.038',
        'inativo' => 'S' // INATIVO na Omie
    ];
    
    echo "Aplicando correção com dados da API:" . PHP_EOL;
    echo "- inativo: 'S' (inativo na Omie)" . PHP_EOL;
    echo "- active: false (inativo localmente)" . PHP_EOL;
    echo PHP_EOL;
    
    // Atualizar usando o método do modelo
    $centro->atualizarDadosOmie($dadosOmieCorretos);
    
    // Recarregar o registro para verificar
    $centro->refresh();
    
    echo "✓ Correção aplicada - Status DEPOIS da correção:" . PHP_EOL;
    echo "Omie Inativo: '" . $centro->omie_inativo . "'" . PHP_EOL;
    echo "Active (campo local): " . ($centro->active ? 'true' : 'false') . PHP_EOL;
    echo "isAtivoOmie(): " . ($centro->isAtivoOmie() ? 'true' : 'false') . PHP_EOL;
    echo "Sincronizado em: " . $centro->sincronizado_em . PHP_EOL;
    echo PHP_EOL;
    
    // Verificar filtros após correção
    $ativosOmie = CentroCusto::ativosOmie()->where('omie_codigo', '3344966640')->count();
    $inativosOmie = CentroCusto::inativosOmie()->where('omie_codigo', '3344966640')->count();
    
    echo "Filtros de escopo após correção:" . PHP_EOL;
    echo "- Aparece em ativosOmie(): " . ($ativosOmie > 0 ? 'SIM' : 'NÃO') . PHP_EOL;
    echo "- Aparece em inativosOmie(): " . ($inativosOmie > 0 ? 'SIM' : 'NÃO') . PHP_EOL;
    
} else {
    echo "✗ Registro não encontrado no banco de dados" . PHP_EOL;
}

echo PHP_EOL . "Correção concluída." . PHP_EOL;