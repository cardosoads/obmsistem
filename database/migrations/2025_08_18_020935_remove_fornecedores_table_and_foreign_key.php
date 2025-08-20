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
        // Primeiro, remover a chave estrangeira e a coluna fornecedor_id da tabela orcamento_prestador
        Schema::table('orcamento_prestador', function (Blueprint $table) {
            $table->dropForeign(['fornecedor_id']);
            $table->dropColumn('fornecedor_id');
            
            // Adicionar novas colunas para integração OMIE
            $table->integer('fornecedor_omie_id')->nullable()->after('orcamento_id');
            $table->string('fornecedor_nome')->nullable()->after('fornecedor_omie_id');
        });
        
        // Depois, remover a tabela fornecedores
        Schema::dropIfExists('fornecedores');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recriar a tabela fornecedores
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        
        // Restaurar a coluna fornecedor_id e a chave estrangeira
        Schema::table('orcamento_prestador', function (Blueprint $table) {
            $table->dropColumn(['fornecedor_omie_id', 'fornecedor_nome']);
            $table->foreignId('fornecedor_id')->after('orcamento_id')->constrained('fornecedores');
        });
    }
};
