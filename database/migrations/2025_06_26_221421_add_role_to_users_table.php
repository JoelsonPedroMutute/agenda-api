<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a modificação na tabela 'users', adicionando a coluna 'role'.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 'after' é ignorado em PostgreSQL, por isso removido
            $table->string('role')->default('user'); // Perfil do usuário: 'user', 'admin', etc.
        });
    }

    /**
     * Reverte a modificação feita na tabela 'users'.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
