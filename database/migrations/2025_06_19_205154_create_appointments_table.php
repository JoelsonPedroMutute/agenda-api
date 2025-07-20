<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a migration.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id'); // Chave primária (BIGSERIAL)

            $table->foreignId('user_id')
                ->constrained('users')     // FK para a tabela 'users'
                ->onDelete('cascade');     // Exclui agendamentos ao excluir usuário

            $table->string('title'); // Título do agendamento

            $table->text('description')->nullable(); // Descrição longa, opcional

            $table->date('date');           // Data do agendamento
            $table->time('start_time');     // Início
            $table->time('end_time');       // Fim

            $table->enum('status', ['ativo', 'cancelado', 'concluido']) // Status
                ->default('ativo');

            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverte a migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
