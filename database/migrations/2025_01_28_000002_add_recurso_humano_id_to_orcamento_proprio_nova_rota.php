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
            // Adicionar campo recurso_humano_id após tem_funcionario
            $table->foreignId('recurso_humano_id')
                  ->nullable()
                  ->after('tem_funcionario')
                  ->constrained('recursos_humanos')
                  ->onDelete('set null')
                  ->comment('Referência ao funcionário selecionado da tabela recursos_humanos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamento_proprio_nova_rota', function (Blueprint $table) {
            $table->dropForeign(['recurso_humano_id']);
            $table->dropColumn('recurso_humano_id');
        });
    }
};