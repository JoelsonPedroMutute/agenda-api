<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a migration: adiciona a coluna 'deleted_at' para soft deletes na tabela 'users'.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes(); // Cria a coluna 'deleted_at' do tipo timestamp nullable
        });
    }

    /**
     * Reverte a migration: remove a coluna 'deleted_at'.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Remove a coluna 'deleted_at'
        });
    }
};
