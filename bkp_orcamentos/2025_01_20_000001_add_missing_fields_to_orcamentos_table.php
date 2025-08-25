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
        Schema::table('orcamentos', function (Blueprint $table) {
            // Adicionar campos que estão no modelo mas não na migração original
            $table->datetime('data_aprovacao')->nullable()->after('data_validade')->comment('Data de aprovação do orçamento');
            $table->datetime('data_inicio')->nullable()->after('data_aprovacao')->comment('Data de início do orçamento');
            $table->datetime('data_exclusao')->nullable()->after('data_inicio')->comment('Data de exclusão do orçamento');
            $table->datetime('data_envio')->nullable()->after('data_exclusao')->comment('Data de envio do orçamento');
            $table->text('detalhes')->nullable()->after('data_envio')->comment('Detalhes adicionais do orçamento');
            $table->string('evento', 255)->nullable()->after('frequencia_atendimento')->comment('Tipo de evento do orçamento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropColumn([
                'data_aprovacao',
                'data_inicio', 
                'data_exclusao',
                'data_envio',
                'detalhes',
                'evento'
            ]);
        });
    }
};