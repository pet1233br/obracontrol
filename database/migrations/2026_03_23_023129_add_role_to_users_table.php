<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Executa a migration
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('funcionario'); // adiciona coluna role
        });
    }

    // Reverte a migration
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role'); // remove coluna role
        });
    }
};