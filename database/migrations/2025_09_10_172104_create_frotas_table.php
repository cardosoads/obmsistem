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
        Schema::create('frotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_veiculo_id')->constrained('tipos_veiculos')->onDelete('restrict')->comment('Tipo de veículo');
            $table->decimal('fipe', 12, 2)->comment('Valor Fipe de referência');
            $table->decimal('percentual_fipe', 5, 2)->default(0)->comment('Percentual sobre Fipe');
            $table->decimal('aluguel_carro', 12, 2)->comment('Valor do aluguel do carro');
            $table->decimal('rastreador', 12, 2)->default(0)->comment('Custo do rastreador');
            $table->decimal('provisoes_avarias', 12, 2)->default(0)->comment('Provisões para avarias/manutenção');
            $table->decimal('provisao_desmobilizacao', 12, 2)->default(0)->comment('Provisão para desmobilização');
            $table->decimal('provisao_diaria_rac', 12, 2)->default(0)->comment('Provisão diária RAC');
            $table->decimal('custo_total', 12, 2)->default(0)->comment('Custo total da frota (calculado)');
            $table->boolean('active')->default(true)->comment('Status ativo/inativo');
            $table->timestamps();
            
            // Índices
            $table->index('tipo_veiculo_id');
            $table->index('active');
            $table->index('custo_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frotas');
    }
};
