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
            $table->date('data_solicitacao')->comment('Data de solicitação do orçamento');
            $table->foreignId('centro_custo_id')->constrained('centros_custo')->onDelete('cascade');
            $table->string('numero_orcamento', 50)->unique()->comment('Número do orçamento (gerado automaticamente)');
            $table->string('nome_rota', 255)->comment('Nome da rota');
            $table->string('id_logcare', 100)->nullable()->comment('ID do LOGCARE');
            $table->unsignedBigInteger('cliente_omie_id')->nullable()->comment('ID do cliente na API OMIE');
            $table->string('cliente_nome', 255)->nullable()->comment('Nome do cliente da API OMIE');
            $table->string('horario', 100)->nullable()->comment('Horário de atendimento');
            $table->string('frequencia_atendimento', 100)->nullable()->comment('Frequência de atendimento');
            $table->enum('tipo_orcamento', ['prestador', 'aumento_km', 'proprio_nova_rota'])->comment('Tipo do orçamento');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('data_orcamento');
            $table->date('data_validade')->nullable();
            $table->decimal('valor_total', 15, 2)->default(0);
            $table->decimal('valor_impostos', 15, 2)->default(0);
            $table->decimal('valor_final', 15, 2)->default(0);
            $table->enum('status', ['rascunho', 'enviado', 'aprovado', 'rejeitado', 'cancelado'])->default('rascunho');
            $table->text('observacoes')->nullable();
            $table->timestamps();
            
            // Índices para otimização
            $table->index(['cliente_omie_id', 'data_orcamento']);
            $table->index(['status', 'data_orcamento']);
            $table->index(['user_id', 'data_orcamento']);
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
