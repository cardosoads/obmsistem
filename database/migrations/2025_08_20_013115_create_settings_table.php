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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique()->comment('Chave da configuração');
            $table->text('value')->nullable()->comment('Valor da configuração');
            $table->string('type', 50)->default('string')->comment('Tipo do valor: string, boolean, integer, json');
            $table->string('group', 50)->default('general')->comment('Grupo da configuração: general, omie, email, etc');
            $table->text('description')->nullable()->comment('Descrição da configuração');
            $table->boolean('is_encrypted')->default(false)->comment('Se o valor está criptografado');
            $table->timestamps();
            
            // Índices para otimização
            $table->index(['group', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
