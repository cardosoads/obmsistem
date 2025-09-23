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
        Schema::table('orcamento_proprio_nova_rota', function (Blueprint $table) {
            // Campos para funcionário
            $table->boolean('tem_funcionario')->default(false)->after('observacoes');
            $table->string('cargo_funcionario')->nullable()->after('tem_funcionario');
            $table->foreignId('base_funcionario_id')->nullable()->constrained('bases')->onDelete('set null')->after('cargo_funcionario');
            $table->decimal('valor_funcionario', 10, 2)->nullable()->after('base_funcionario_id');
            
            // Campos para veículo próprio
            $table->boolean('tem_veiculo_proprio')->default(false)->after('valor_funcionario');
            $table->foreignId('frota_id')->nullable()->constrained('frotas')->onDelete('set null')->after('tem_veiculo_proprio');
            $table->decimal('valor_aluguel_veiculo', 10, 2)->nullable()->after('frota_id');
            
            // Campos para prestador
            $table->boolean('tem_prestador')->default(false)->after('valor_aluguel_veiculo');
            $table->string('fornecedor_omie_id')->nullable()->after('tem_prestador');
            $table->string('fornecedor_nome')->nullable()->after('fornecedor_omie_id');
            $table->decimal('valor_referencia_prestador', 10, 2)->nullable()->after('fornecedor_nome');
            $table->integer('qtd_dias_prestador')->nullable()->after('valor_referencia_prestador');
            $table->decimal('custo_prestador', 10, 2)->nullable()->after('qtd_dias_prestador');
            $table->decimal('lucro_percentual_prestador', 5, 2)->nullable()->after('custo_prestador');
            $table->decimal('valor_lucro_prestador', 10, 2)->nullable()->after('lucro_percentual_prestador');
            $table->decimal('impostos_percentual_prestador', 5, 2)->nullable()->after('valor_lucro_prestador');
            $table->decimal('valor_impostos_prestador', 10, 2)->nullable()->after('impostos_percentual_prestador');
            $table->decimal('valor_total_prestador', 10, 2)->nullable()->after('valor_impostos_prestador');
            $table->foreignId('grupo_imposto_prestador_id')->nullable()->constrained('grupos_impostos')->onDelete('set null')->after('valor_total_prestador');
            
            // Campo para valor total geral
            $table->decimal('valor_total_geral', 10, 2)->nullable()->after('grupo_imposto_prestador_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamento_proprio_nova_rota', function (Blueprint $table) {
            $table->dropForeign(['grupo_imposto_prestador_id']);
            $table->dropForeign(['frota_id']);
            $table->dropForeign(['base_funcionario_id']);
            
            $table->dropColumn([
                'tem_funcionario',
                'cargo_funcionario',
                'base_funcionario_id',
                'valor_funcionario',
                'tem_veiculo_proprio',
                'frota_id',
                'valor_aluguel_veiculo',
                'tem_prestador',
                'fornecedor_omie_id',
                'fornecedor_nome',
                'valor_referencia_prestador',
                'qtd_dias_prestador',
                'custo_prestador',
                'lucro_percentual_prestador',
                'valor_lucro_prestador',
                'impostos_percentual_prestador',
                'valor_impostos_prestador',
                'valor_total_prestador',
                'grupo_imposto_prestador_id',
                'valor_total_geral'
            ]);
        });
    }
};