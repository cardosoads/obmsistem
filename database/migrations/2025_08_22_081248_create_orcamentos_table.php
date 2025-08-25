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
        Schema::create('orcamentos', function (Blueprint $table) {
            $table->id();
            $table->date('data_solicitacao');
            $table->unsignedBigInteger('centro_custo_id');
            $table->string('numero_orcamento')->unique();
            $table->string('nome_rota');
            $table->string('id_logcare')->nullable();
            $table->string('cliente_omie_id')->nullable();
            $table->string('cliente_nome')->nullable();
            $table->time('horario')->nullable();
            $table->string('frequencia_atendimento')->nullable();
            $table->enum('tipo_orcamento', ['prestador', 'aumento_km', 'proprio_nova_rota']);
            $table->unsignedBigInteger('user_id');
            $table->date('data_orcamento');
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->decimal('valor_impostos', 10, 2)->default(0);
            $table->decimal('valor_final', 10, 2)->default(0);
            $table->enum('status', ['rascunho', 'enviado', 'aprovado', 'rejeitado', 'cancelado'])->default('rascunho');
            $table->text('observacoes')->nullable();
            $table->timestamps();

            // Índices para performance
            $table->index(['cliente_omie_id', 'data_orcamento']);
            $table->index(['status', 'data_orcamento']);
            $table->index(['user_id', 'data_orcamento']);

            // Chaves estrangeiras
            $table->foreign('centro_custo_id')->references('id')->on('centros_custo');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamentos');
    }
};
