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
            $table->decimal('percentual_lucro', 5, 2)->default(0)->after('valor_final');
            $table->decimal('percentual_impostos', 5, 2)->default(0)->after('percentual_lucro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropColumn(['percentual_lucro', 'percentual_impostos']);
        });
    }
};