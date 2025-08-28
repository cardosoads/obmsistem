<?php

require_once __DIR__ . '/vendor/autoload.php';

// Carregar configuração do Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\CentroCusto;

echo "Verificação final do estado dos centros de custo após correções..." . PHP_EOL . PHP_EOL;

// Estatísticas gerais
$totalRegistros = CentroCusto::count();
$registrosSincronizados = CentroCusto::whereNotNull('omie_codigo')->count();
$registrosNaoSincronizados = CentroCusto::whereNull('omie_codigo')->count();

echo "📊 Estatísticas Gerais:" . PHP_EOL;
echo "Total de registros: {$totalRegistros}" . PHP_EOL;
echo "Registros sincronizados: {$registrosSincronizados}" . PHP_EOL;
echo "Registros não sincronizados: {$registrosNaoSincronizados}" . PHP_EOL;
echo PHP_EOL;

// Estatísticas dos registros sincronizados
$ativosOmie = CentroCusto::ativosOmie()->count();
$inativosOmie = CentroCusto::inativosOmie()->count();

// Contagem manual para verificação
$ativosOmieManual = CentroCusto::whereNotNull('omie_codigo')
    ->where('omie_inativo', 'N')
    ->count();
    
$inativosOmieManual = CentroCusto::whereNotNull('omie_codigo')
    ->where('omie_inativo', 'S')
    ->count();

echo "🔄 Status na API Omie (registros sincronizados):" . PHP_EOL;
echo "Ativos na Omie (escopo): {$ativosOmie}" . PHP_EOL;
echo "Ativos na Omie (manual): {$ativosOmieManual}" . PHP_EOL;
echo "Inativos na Omie (escopo): {$inativosOmie}" . PHP_EOL;
echo "Inativos na Omie (manual): {$inativosOmieManual}" . PHP_EOL;
echo PHP_EOL;

// Verificar consistência entre status Omie e status local
$consistentes = CentroCusto::whereNotNull('omie_codigo')
    ->where(function($query) {
        $query->where(function($q) {
            // Ativo na Omie e ativo localmente
            $q->where('omie_inativo', 'N')->where('active', true);
        })->orWhere(function($q) {
            // Inativo na Omie e inativo localmente
            $q->where('omie_inativo', 'S')->where('active', false);
        });
    })
    ->count();

$inconsistentes = CentroCusto::whereNotNull('omie_codigo')
    ->where(function($query) {
        $query->where(function($q) {
            // Ativo na Omie mas inativo localmente
            $q->where('omie_inativo', 'N')->where('active', false);
        })->orWhere(function($q) {
            // Inativo na Omie mas ativo localmente
            $q->where('omie_inativo', 'S')->where('active', true);
        });
    })
    ->count();

echo "✅ Consistência Status Omie vs Local:" . PHP_EOL;
echo "Registros consistentes: {$consistentes}" . PHP_EOL;
echo "Registros inconsistentes: {$inconsistentes}" . PHP_EOL;
echo PHP_EOL;

// Listar registros inativos na Omie para verificação
if ($inativosOmie > 0) {
    echo "📋 Registros inativos na Omie:" . PHP_EOL;
    $registrosInativos = CentroCusto::inativosOmie()->get();
    
    foreach ($registrosInativos as $registro) {
        echo "  - ID: {$registro->id} | Código: {$registro->codigo} | Omie: {$registro->omie_codigo} | Local: " . ($registro->active ? 'Ativo' : 'Inativo') . PHP_EOL;
    }
    echo PHP_EOL;
}

// Verificar o registro específico mencionado
echo "🔍 Verificação do registro específico (3344966640):" . PHP_EOL;
$registroEspecifico = CentroCusto::where('omie_codigo', '3344966640')->first();

if ($registroEspecifico) {
    echo "✓ Encontrado: {$registroEspecifico->codigo}" . PHP_EOL;
    echo "  Status Omie: " . ($registroEspecifico->omie_inativo === 'N' ? 'Ativo' : 'Inativo') . PHP_EOL;
    echo "  Status Local: " . ($registroEspecifico->active ? 'Ativo' : 'Inativo') . PHP_EOL;
    echo "  Aparece em ativosOmie(): " . (CentroCusto::ativosOmie()->where('omie_codigo', '3344966640')->exists() ? 'SIM' : 'NÃO') . PHP_EOL;
    echo "  Aparece em inativosOmie(): " . (CentroCusto::inativosOmie()->where('omie_codigo', '3344966640')->exists() ? 'SIM' : 'NÃO') . PHP_EOL;
} else {
    echo "✗ Registro não encontrado" . PHP_EOL;
}

echo PHP_EOL;

if ($inconsistentes === 0) {
    echo "🎉 SUCESSO: Todas as inconsistências foram corrigidas!" . PHP_EOL;
    echo "Os filtros de status da Omie agora mostram apenas registros sincronizados e consistentes." . PHP_EOL;
} else {
    echo "⚠️  ATENÇÃO: Ainda existem {$inconsistentes} registros inconsistentes." . PHP_EOL;
}

echo PHP_EOL . "Verificação concluída." . PHP_EOL;