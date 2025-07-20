<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrations.
     */
    public function up(): void
    {
        // Tabela de usuários
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigint autoincrementável
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken(); // token de "lembrar-me"
            $table->timestamps(); // created_at e updated_at
        });

        // Tabela de tokens de redefinição de senha
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 255)->primary(); // tamanho fixado para evitar erro no PostgreSQL
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabela de sessões
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 255)->primary(); // chave primária do tipo string com tamanho fixado
            $table->foreignId('user_id')->nullable()->index(); // FK opcional (sem constrained para evitar erro)
            $table->string('ip_address', 45)->nullable(); // suporta IPv4 e IPv6
            $table->text('user_agent')->nullable(); // navegador, sistema operacional etc.
            $table->longText('payload'); // dados da sessão serializados
            $table->integer('last_activity')->index(); // timestamp da última atividade
        });
    }

    /**
     * Reverte as migrations.
     */
    public function down(): void
    {
        // A ordem importa! Apague tabelas filhas primeiro
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
