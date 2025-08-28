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
            // Campos vindos da API Omie
            $table->string('omie_codigo', 40)->nullable()->unique()->after('codigo')->comment('Código único do departamento na API Omie');
            $table->string('omie_estrutura', 40)->nullable()->after('omie_codigo')->comment('Estrutura hierárquica do departamento na Omie');
            $table->char('omie_inativo', 1)->default('N')->after('omie_estrutura')->comment('Status inativo na Omie (S/N)');
            $table->timestamp('sincronizado_em')->nullable()->after('omie_inativo')->comment('Data/hora da última sincronização com a API');
            
            // Índices para performance
            $table->index('omie_codigo');
            $table->index('omie_inativo');
            $table->index('sincronizado_em');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('centros_custo', function (Blueprint $table) {
            // Remover índices
            $table->dropIndex(['omie_codigo']);
            $table->dropIndex(['omie_inativo']);
            $table->dropIndex(['sincronizado_em']);
            
            // Remover colunas
            $table->dropColumn([
                'omie_codigo',
                'omie_estrutura', 
                'omie_inativo',
                'sincronizado_em'
            ]);
        });
    }
};
