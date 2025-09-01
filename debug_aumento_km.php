<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Orcamento;

echo "=== DEBUG AUMENTO KM ===\n";

// Buscar um orçamento de aumento KM
$orcamento = Orcamento::with('orcamentoAumentoKm')
    ->where('tipo_orcamento', 'aumento_km')
    ->first();

if (!$orcamento) {
    echo "Nenhum orçamento de aumento KM encontrado\n";
    exit;
}

if (!$orcamento->orcamentoAumentoKm) {
    echo "Orçamento #{$orcamento->id} não possui dados de aumento KM\n";
    exit;
}

$aumentoKm = $orcamento->orcamentoAumentoKm;

echo "Orçamento ID: {$orcamento->id}\n";
echo "Dados ANTES do cálculo:\n";
echo "- KM Dia: {$aumentoKm->km_dia}\n";
echo "- Qtd Dias: {$aumentoKm->qtd_dias}\n";
echo "- Combustível KM/L: {$aumentoKm->combustivel_km_litro}\n";
echo "- Valor Combustível: {$aumentoKm->valor_combustivel}\n";
echo "- Hora Extra: {$aumentoKm->hora_extra}\n";
echo "- Pedágio: {$aumentoKm->pedagio}\n";
echo "- Lucro %: {$aumentoKm->lucro_percentual}\n";
echo "- Impostos %: {$aumentoKm->impostos_percentual}\n";
echo "- Valor Total ATUAL: {$aumentoKm->valor_total}\n";

echo "\nExecutando calcularValores()...\n";
$aumentoKm->calcularValores();

echo "\nDados DEPOIS do cálculo (antes de salvar):\n";
echo "- KM Total Mês: {$aumentoKm->km_total_mes}\n";
echo "- Total Combustível: {$aumentoKm->total_combustivel}\n";
echo "- Custo Total: {$aumentoKm->custo_total_combustivel_he}\n";
echo "- Valor Lucro: {$aumentoKm->valor_lucro}\n";
echo "- Valor Impostos: {$aumentoKm->valor_impostos}\n";
echo "- Valor Total: {$aumentoKm->valor_total}\n";

echo "\n=== FIM DEBUG ===\n";