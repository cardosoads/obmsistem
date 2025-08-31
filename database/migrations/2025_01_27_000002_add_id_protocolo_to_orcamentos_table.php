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
            // Verifica se a coluna já existe
            if (!Schema::hasColumn('orcamentos', 'id_protocolo')) {
                $table->string('id_protocolo')->nullable()->comment('ID de Protocolo digitado pelo usuário')->after('centro_custo_id');
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
            // Verifica se a coluna existe antes de tentar removê-la
            if (Schema::hasColumn('orcamentos', 'id_protocolo')) {
                $table->dropColumn('id_protocolo');
            }
        });
    }
};