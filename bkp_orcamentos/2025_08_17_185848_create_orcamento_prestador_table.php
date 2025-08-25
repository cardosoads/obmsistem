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
        Schema::create('orcamento_prestador', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamento_id')->constrained('orcamentos')->onDelete('cascade');
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->onDelete('cascade');
            $table->decimal('valor_referencia', 15, 2)->comment('Valor de referência do fornecedor');
            $table->integer('qtd_dias')->comment('Quantidade de dias');
            $table->decimal('custo_fornecedor', 15, 2)->comment('Custo do fornecedor');
            $table->decimal('lucro_percentual', 5, 2)->comment('Percentual de lucro');
            $table->decimal('valor_lucro', 15, 2)->comment('Valor do lucro calculado');
            $table->decimal('impostos_percentual', 5, 2)->comment('Percentual de impostos');
            $table->decimal('valor_impostos', 15, 2)->comment('Valor dos impostos calculado');
            $table->decimal('valor_total', 15, 2)->comment('Valor total final');
            $table->text('observacoes')->nullable();
            $table->timestamps();
            
            // Índice composto para otimização
            $table->index(['orcamento_id', 'fornecedor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamento_prestador');
    }
};
