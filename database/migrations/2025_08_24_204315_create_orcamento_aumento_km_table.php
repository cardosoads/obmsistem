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
            $table->decimal('km_dia', 8, 2)->nullable();
            $table->integer('qtd_dias')->nullable();
            $table->decimal('km_total_mes', 10, 2)->nullable();
            $table->decimal('combustivel_km_litro', 8, 2)->nullable();
            $table->decimal('total_combustivel', 10, 2)->nullable();
            $table->decimal('valor_combustivel', 8, 2)->nullable();
            $table->decimal('hora_extra', 8, 2)->nullable();
            $table->decimal('custo_total_combustivel_he', 10, 2)->nullable();
            $table->decimal('lucro_percentual', 5, 2)->nullable();
            $table->decimal('valor_lucro', 10, 2)->nullable();
            $table->decimal('impostos_percentual', 5, 2)->nullable();
            $table->decimal('valor_impostos', 10, 2)->nullable();
            $table->decimal('valor_total', 10, 2)->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
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
