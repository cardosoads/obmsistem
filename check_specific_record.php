<?php

require_once __DIR__ . '/vendor/autoload.php';

// Carregar configuração do Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CentroCusto;

echo "Verificando registro com omie_codigo '3344966640'..." . PHP_EOL . PHP_EOL;

$centro = CentroCusto::where('omie_codigo', '3344966640')->first();

if ($centro) {
    echo "✓ Registro encontrado:" . PHP_EOL;
    echo "ID: " . $centro->id . PHP_EOL;
    echo "Código: " . $centro->codigo . PHP_EOL;
    echo "Nome: " . ($centro->name ?? 'NULL') . PHP_EOL;
    echo "Descrição: " . ($centro->description ?? 'NULL') . PHP_EOL;
    echo "Omie Código: " . $centro->omie_codigo . PHP_EOL;
    echo "Omie Estrutura: " . ($centro->omie_estrutura ?? 'NULL') . PHP_EOL;
    echo "Omie Inativo: '" . $centro->omie_inativo . "'" . PHP_EOL;
    echo "Active (campo local): " . ($centro->active ? 'true' : 'false') . PHP_EOL;
    echo "Sincronizado em: " . ($centro->sincronizado_em ?? 'NULL') . PHP_EOL;
    echo PHP_EOL;
    
    // Verificar status usando métodos do modelo
    echo "Status calculados:" . PHP_EOL;
    echo "- isSincronizado(): " . ($centro->isSincronizado() ? 'true' : 'false') . PHP_EOL;
    echo "- isAtivoOmie(): " . ($centro->isAtivoOmie() ? 'true' : 'false') . PHP_EOL;
    echo PHP_EOL;
    
    // Verificar se está sendo filtrado pelos escopos
    $ativosOmie = CentroCusto::ativosOmie()->where('omie_codigo', '3344966640')->count();
    $inativosOmie = CentroCusto::inativosOmie()->where('omie_codigo', '3344966640')->count();
    
    echo "Filtros de escopo:" . PHP_EOL;
    echo "- Aparece em ativosOmie(): " . ($ativosOmie > 0 ? 'SIM' : 'NÃO') . PHP_EOL;
    echo "- Aparece em inativosOmie(): " . ($inativosOmie > 0 ? 'SIM' : 'NÃO') . PHP_EOL;
    
} else {
    echo "✗ Registro não encontrado no banco de dados" . PHP_EOL;
    
    // Verificar se existe com código '3BA23'
    $centroPorCodigo = CentroCusto::where('codigo', '3BA23')->first();
    if ($centroPorCodigo) {
        echo "Mas encontrado registro com código '3BA23':" . PHP_EOL;
        echo "ID: " . $centroPorCodigo->id . PHP_EOL;
        echo "Omie Código: " . ($centroPorCodigo->omie_codigo ?? 'NULL') . PHP_EOL;
        echo "Omie Inativo: '" . $centroPorCodigo->omie_inativo . "'" . PHP_EOL;
    }
}

echo PHP_EOL . "Verificação concluída." . PHP_EOL;