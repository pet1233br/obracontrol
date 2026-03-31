<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produto_empresa', function (Blueprint $table) {
            // Adiciona a coluna de preço de custo
            $table->decimal('preco_custo', 10, 2)->default(0)->after('quantidade');
        });
    }

    public function down(): void
    {
        Schema::table('produto_empresa', function (Blueprint $table) {
            $table->dropColumn('preco_custo');
        });
    }
};
