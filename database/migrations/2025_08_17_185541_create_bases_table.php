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
        Schema::create('bases', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('Nome da cidade');
            $table->string('uf', 2)->comment('Estado');
            $table->string('regional', 100)->comment('Região');
            $table->string('sigla', 3)->comment('3 caracteres, maiúsculo');
            $table->string('supervisor', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bases');
    }
};
