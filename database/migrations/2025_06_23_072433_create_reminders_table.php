<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a criação da tabela reminders.
     */
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->bigIncrements('id'); // Chave primária (BIGSERIAL)
            
            $table->foreignId('appointment_id')
                ->constrained('appointments') // Cria foreign key para tabela 'appointments'
                ->onDelete('cascade');        // Apaga os lembretes quando o agendamento for deletado

            $table->timestamp('remind_at')->nullable(); // Data/hora para o lembrete

            $table->enum('method', ['email', 'message', 'notification']) // Meio do lembrete
                  ->default('notification');

            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverte a criação da tabela reminders.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
