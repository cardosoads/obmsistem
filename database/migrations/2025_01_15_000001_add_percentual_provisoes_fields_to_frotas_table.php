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
        Schema::table('frotas', function (Blueprint $table) {
            // Adicionar campos de percentuais para provisões
            $table->decimal('percentual_provisoes_avarias', 5, 2)->default(0)->after('rastreador')->comment('Percentual para cálculo de provisões de avarias');
            $table->decimal('percentual_provisao_desmobilizacao', 5, 2)->default(0)->after('provisoes_avarias')->comment('Percentual para cálculo de provisão de desmobilização');
            $table->decimal('percentual_provisao_rac', 5, 2)->default(0)->after('provisao_desmobilizacao')->comment('Percentual para cálculo de provisão diária RAC');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('frotas', function (Blueprint $table) {
            $table->dropColumn([
                'percentual_provisoes_avarias',
                'percentual_provisao_desmobilizacao',
                'percentual_provisao_rac'
            ]);
        });
    }
};