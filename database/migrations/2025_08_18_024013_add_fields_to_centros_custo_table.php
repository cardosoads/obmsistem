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
        Schema::table('centros_custo', function (Blueprint $table) {
            // Campo CC - já existe como 'codigo'
            
            // Campos do cliente OMIE
            $table->string('cliente_omie_id', 50)->nullable()->comment('ID do cliente na API OMIE');
            $table->string('cliente_nome', 255)->nullable()->comment('Nome do cliente trazido da API OMIE');
            
            // Relacionamento com base
            $table->foreignId('base_id')->nullable()->constrained('bases')->onDelete('set null');
            
            // Campos trazidos da base (não preenchíveis diretamente)
            $table->string('regional', 100)->nullable()->comment('Regional da base selecionada');
            $table->string('sigla', 10)->nullable()->comment('Sigla da base selecionada');
            $table->string('uf', 2)->nullable()->comment('UF da base selecionada');
            $table->string('supervisor', 255)->nullable()->comment('Supervisor da base selecionada');
            
            // Relacionamento com marca
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->onDelete('set null');
            
            // Campo mercado
            $table->string('mercado', 100)->nullable()->comment('Mercado do centro de custo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('centros_custo', function (Blueprint $table) {
            $table->dropForeign(['marca_id']);
            $table->dropColumn('marca_id');
            $table->dropColumn('mercado');
            $table->dropColumn('supervisor');
            $table->dropColumn('uf');
            $table->dropColumn('sigla');
            $table->dropColumn('regional');
            $table->dropForeign(['base_id']);
            $table->dropColumn('base_id');
            $table->dropColumn('cliente_nome');
            $table->dropColumn('cliente_omie_id');
        });
    }
};
