<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Classe responsável por enviar uma notificação de redefinição de senha por e-mail.
 * Essa notificação é personalizada com um link contendo o token de redefinição.
 */
class ResetPasswordNotification extends Notification
{
    /**
     * Token único de redefinição de senha gerado pelo Laravel.
     *
     * @var string
     */
    public $token;

    /**
     * Construtor da classe.
     *
     * @param string $token  Token de redefinição de senha gerado pelo Laravel.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Define os canais de entrega da notificação.
     *
     * @param mixed $notifiable
     * @return array  Neste caso, apenas 'mail' (envio por e-mail).
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Define o conteúdo do e-mail que será enviado ao usuário.
     *
     * @param mixed $notifiable  O usuário que receberá o e-mail.
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Gera a URL do link de redefinição de senha com base no token e e-mail do usuário
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Cria a estrutura do e-mail com título, mensagem, botão e rodapé
        return (new MailMessage)
            ->subject('Redefinição de senha') // Assunto do e-mail
            ->greeting('Olá!') // Saudação inicial
            ->line('Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta.')
            ->action('Redefinir senha', $url) // Botão com link para redefinir a senha
            ->line('Este link de redefinição de senha expirará em 60 minutos.')
            ->line('Se você não solicitou uma redefinição de senha, nenhuma ação é necessária.')
            ->salutation('Atenciosamente, Equipe Agenda API'); // Rodapé
    }
}
