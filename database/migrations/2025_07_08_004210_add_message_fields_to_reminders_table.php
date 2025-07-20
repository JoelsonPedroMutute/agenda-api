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
        Schema::table('reminders', function (Blueprint $table) {
            // No PostgreSQL, a ordem das colunas não pode ser garantida com "after".
            $table->string('message_status')->nullable(); // Adiciona campo para status da mensagem
            $table->string('message_sid')->nullable();     // Adiciona campo para SID da mensagem (ex: Twilio)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn(['message_status', 'message_sid']);
        });
    }
};
