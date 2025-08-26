<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Base;

echo "Verificando bases mais recentes:\n";
echo "Total de bases: " . Base::count() . "\n\n";

echo "Últimas 10 bases criadas (ordenadas por data):\n";
$bases = Base::orderBy('created_at', 'desc')->limit(10)->get();
foreach ($bases as $base) {
    echo "- ID: {$base->id}, Nome: {$base->name}, UF: {$base->uf}, Sigla: {$base->sigla}, Supervisor: {$base->supervisor}, Criado em: {$base->created_at}\n";
}

echo "\nVerificando base com sigla RJO:\n";
$baseRJO = Base::where('sigla', 'RJO')->first();
if ($baseRJO) {
    echo "Base RJO encontrada!\n";
    echo "- ID: {$baseRJO->id}\n";
    echo "- Nome: {$baseRJO->name}\n";
    echo "- UF: {$baseRJO->uf}\n";
    echo "- Regional: {$baseRJO->regional}\n";
    echo "- Supervisor: {$baseRJO->supervisor}\n";
    echo "- Criado em: {$baseRJO->created_at}\n";
} else {
    echo "Base com sigla RJO não foi encontrada.\n";
}