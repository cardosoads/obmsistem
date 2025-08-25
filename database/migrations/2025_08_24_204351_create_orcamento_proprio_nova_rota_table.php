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
            $table->string('nova_origem')->nullable();
            $table->string('novo_destino')->nullable();
            $table->decimal('km_nova_rota', 10, 2)->nullable();
            $table->decimal('valor_km_nova_rota', 8, 2)->nullable();
            $table->decimal('valor_total_nova_rota', 10, 2)->nullable();
            $table->text('motivo_alteracao')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
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
