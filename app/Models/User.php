<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Appointment;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo que representa o usuário do sistema.
 * Inclui autenticação, notificações e relacionamentos com agendamentos.
 */
class User extends Authenticatable 
{
    // Traits que adicionam funcionalidades ao modelo
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * Atributos que podem ser preenchidos via mass assignment.
     * Protege contra falhas de segurança ao usar create() ou update().
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Pode ser 'user' ou 'admin'
        'phone_number',
    ];

    /**
     * Atributos que devem ser ocultados ao serializar (ex: JSON).
     * Protege dados sensíveis como senhas.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Converte atributos para tipos nativos.
     * O campo 'email_verified_at' é tratado como datetime.
     * O campo 'password' será automaticamente criptografado ao ser salvo.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ trata hash automático
    ];

    /**
     * Verifica se o usuário tem papel de administrador.
     * Retorna true se o campo 'role' for igual a 'admin'.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Relacionamento: um usuário pode ter vários agendamentos.
     * Exemplo: $user->appointments
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Sobrescreve o envio da notificação de redefinição de senha.
     * Utiliza uma notificação personalizada (ResetPasswordNotification).
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
