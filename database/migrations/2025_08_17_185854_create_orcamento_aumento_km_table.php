<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orcamento_aumento_km', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamento_id')->constrained('orcamentos')->onDelete('cascade');
            $table->decimal('km_dia', 10, 2)->comment('KM por dia');
            $table->integer('qtd_dias')->comment('Quantidade de dias');
            $table->decimal('km_total_mes', 10, 2)->comment('KM total do mês');
            $table->decimal('combustivel_km_litro', 8, 2)->comment('Combustível KM/Litro');
            $table->decimal('total_combustivel', 10, 2)->comment('Total de combustível');
            $table->decimal('valor_combustivel', 15, 2)->comment('Valor do combustível');
            $table->decimal('hora_extra', 15, 2)->comment('Valor hora extra');
            $table->decimal('custo_total_combustivel_he', 15, 2)->comment('Custo total combustível + HE');
            $table->decimal('lucro_percentual', 5, 2)->comment('Percentual de lucro');
            $table->decimal('valor_lucro', 15, 2)->comment('Valor do lucro calculado');
            $table->decimal('impostos_percentual', 5, 2)->comment('Percentual de impostos');
            $table->decimal('valor_impostos', 15, 2)->comment('Valor dos impostos calculado');
            $table->decimal('valor_total', 15, 2)->comment('Valor total final');
            $table->text('observacoes')->nullable();
            $table->timestamps();
            
            // Índice para otimização
            $table->index('orcamento_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamento_aumento_km');
    }
};
