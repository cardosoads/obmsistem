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
        // Verifica se os índices já existem antes de tentar criá-los
        $existingIndexes = collect(\DB::select("SHOW INDEX FROM centros_custo"))
            ->pluck('Key_name')
            ->toArray();
        
        Schema::table('centros_custo', function (Blueprint $table) use ($existingIndexes) {
            // Índice para filtros por status
            if (!in_array('centros_custo_active_index', $existingIndexes)) {
                $table->index('active', 'centros_custo_active_index');
            }
            
            // Índices compostos para otimizar buscas com filtros
            if (!in_array('centros_custo_name_active_index', $existingIndexes)) {
                $table->index(['name', 'active'], 'centros_custo_name_active_index');
            }
            if (!in_array('centros_custo_codigo_active_index', $existingIndexes)) {
                $table->index(['codigo', 'active'], 'centros_custo_codigo_active_index');
            }
            if (!in_array('centros_custo_cliente_nome_active_index', $existingIndexes)) {
                $table->index(['cliente_nome', 'active'], 'centros_custo_cliente_nome_active_index');
            }
            
            // Índice para ordenação por data de criação (usado na listagem)
            if (!in_array('centros_custo_created_at_index', $existingIndexes)) {
                $table->index('created_at', 'centros_custo_created_at_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('centros_custo', function (Blueprint $table) {
            $table->dropIndex('centros_custo_created_at_index');
            $table->dropIndex('centros_custo_cliente_nome_active_index');
            $table->dropIndex('centros_custo_codigo_active_index');
            $table->dropIndex('centros_custo_name_active_index');
            $table->dropIndex('centros_custo_active_index');
        });
    }
};