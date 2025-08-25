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
        Schema::create('orcamento_proprio_nova_rota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamento_id')->constrained('orcamentos')->onDelete('cascade');
            $table->string('nova_origem', 255);
            $table->string('novo_destino', 255);
            $table->decimal('km_nova_rota', 10, 2);
            $table->decimal('valor_km_nova_rota', 10, 2);
            $table->decimal('valor_total_nova_rota', 15, 2);
            $table->text('motivo_alteracao')->nullable();
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
        Schema::dropIfExists('orcamento_proprio_nova_rota');
    }
};
