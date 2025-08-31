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
        // Verifica se a tabela existe antes de tentar modificá-la
        if (!Schema::hasTable('orcamentos')) {
            // Se a tabela não existe, pula esta migração
            // Isso pode acontecer se as migrações anteriores não foram executadas
            return;
        }
        
        Schema::table('orcamentos', function (Blueprint $table) {
            // Verifica se as colunas já existem
            if (!Schema::hasColumn('orcamentos', 'percentual_lucro')) {
                $table->decimal('percentual_lucro', 5, 2)->nullable()->comment('Percentual de lucro aplicado (apenas para prestador)')->after('valor_final');
            }
            if (!Schema::hasColumn('orcamentos', 'percentual_impostos')) {
                $table->decimal('percentual_impostos', 5, 2)->nullable()->comment('Percentual de impostos aplicado (apenas para prestador)')->after('percentual_lucro');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Verifica se a tabela existe antes de tentar modificá-la
        if (!Schema::hasTable('orcamentos')) {
            return;
        }
        
        Schema::table('orcamentos', function (Blueprint $table) {
            // Verifica se as colunas existem antes de tentar removê-las
            $columnsToRemove = [];
            if (Schema::hasColumn('orcamentos', 'percentual_lucro')) {
                $columnsToRemove[] = 'percentual_lucro';
            }
            if (Schema::hasColumn('orcamentos', 'percentual_impostos')) {
                $columnsToRemove[] = 'percentual_impostos';
            }
            
            if (!empty($columnsToRemove)) {
                $table->dropColumn($columnsToRemove);
            }
        });
    }
};