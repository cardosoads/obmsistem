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
        Schema::create('orcamento_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamento_id')->constrained('orcamentos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('acao', ['criado', 'editado', 'enviado', 'aprovado', 'rejeitado', 'cancelado']);
            $table->string('status_anterior', 50)->nullable();
            $table->string('status_novo', 50)->nullable();
            $table->json('dados_alterados')->nullable()->comment('JSON com os dados que foram alterados');
            $table->text('observacoes')->nullable();
            $table->timestamp('data_acao')->useCurrent();
            $table->timestamps();
            
            // Índices para otimização
            $table->index(['orcamento_id', 'data_acao']);
            $table->index(['user_id', 'data_acao']);
            $table->index('acao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamento_historico');
    }
};
