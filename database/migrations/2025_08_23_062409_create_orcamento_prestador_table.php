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
            $table->unsignedBigInteger('orcamento_id');
            $table->string('fornecedor_omie_id')->nullable();
            $table->string('fornecedor_nome');
            $table->decimal('valor_referencia', 10, 2)->default(0);
            $table->integer('qtd_dias')->default(1);
            $table->decimal('custo_fornecedor', 10, 2)->default(0);
            $table->decimal('lucro_percentual', 5, 2)->default(0);
            $table->decimal('valor_lucro', 10, 2)->default(0);
            $table->decimal('impostos_percentual', 5, 2)->default(0);
            $table->decimal('valor_impostos', 10, 2)->default(0);
            $table->decimal('valor_total', 15, 2)->default(0);

            $table->timestamps();

            // Chave estrangeira
            $table->foreign('orcamento_id')->references('id')->on('orcamentos')->onDelete('cascade');
            
            // Índice para melhor performance
            $table->index('orcamento_id');
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
