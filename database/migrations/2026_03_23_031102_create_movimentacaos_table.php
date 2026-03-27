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
        // Nota: Mudei para 'movimentacoes' (plural correto em PT)
        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->id();
            
            // Conecta com a tabela de produtos (garanta que o nome da tabela seja 'produtos')
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            
            // Conecta com o usuário que está logado
            $table->foreignId('user_id')->constrained('users');
            
            // Tipo: entrada ou saida
            $table->enum('tipo', ['entrada', 'saida']);
            
            $table->integer('quantidade');
            $table->string('observacao')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacoes');
    }
};