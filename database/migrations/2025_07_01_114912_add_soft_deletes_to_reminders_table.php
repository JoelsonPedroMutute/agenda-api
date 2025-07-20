<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a migration: adiciona a coluna 'deleted_at' à tabela 'reminders'.
     */
    public function up(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            // Suporte a exclusão lógica (soft delete)
            $table->softDeletes(); // Cria coluna 'deleted_at' (TIMESTAMP NULL)
        });
    }

    /**
     * Reverte a migration: remove a coluna 'deleted_at'.
     */
    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Remove a coluna 'deleted_at'
        });
    }
};
