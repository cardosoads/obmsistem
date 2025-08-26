<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Base;

echo "Verificando bases no banco de dados:\n";
echo "Total de bases: " . Base::count() . "\n\n";

echo "Últimas 5 bases criadas:\n";
$bases = Base::orderBy('created_at', 'desc')->limit(5)->get();
foreach ($bases as $base) {
    echo "- ID: {$base->id}, Nome: {$base->name}, UF: {$base->uf}, Sigla: {$base->sigla}, Supervisor: {$base->supervisor}, Criado em: {$base->created_at}\n";
}

echo "\nVerificando base com sigla SPL:\n";
$baseSPL = Base::where('sigla', 'SPL')->first();
if ($baseSPL) {
    echo "Base SPL encontrada!\n";
    echo "- ID: {$baseSPL->id}\n";
    echo "- Nome: {$baseSPL->name}\n";
    echo "- UF: {$baseSPL->uf}\n";
    echo "- Regional: {$baseSPL->regional}\n";
    echo "- Supervisor: {$baseSPL->supervisor}\n";
    echo "- Criado em: {$baseSPL->created_at}\n";
} else {
    echo "Base com sigla SPL não foi encontrada.\n";
}