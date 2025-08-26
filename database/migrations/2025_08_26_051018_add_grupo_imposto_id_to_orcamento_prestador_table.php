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
        Schema::table('orcamento_prestador', function (Blueprint $table) {
            $table->foreignId('grupo_imposto_id')->nullable()->after('valor_total')->constrained('grupos_impostos')->onDelete('set null');
            $table->text('observacoes')->nullable()->after('grupo_imposto_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamento_prestador', function (Blueprint $table) {
            $table->dropForeign(['grupo_imposto_id']);
            $table->dropColumn(['grupo_imposto_id', 'observacoes']);
        });
    }
};
