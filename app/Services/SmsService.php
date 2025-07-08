<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Envia uma mensagem para um número utilizando sistema externo (fora do Laravel).
     *
     * @param string $to Número do destinatário
     * @param string $message Conteúdo da mensagem
     * @return array Resultado do envio
     */
    public function send(string $to, string $message): array
    {
        // Simulação de envio externo
        Log::info("Simulando envio de SMS para {$to} com a seguinte mensagem: {$message}");

        // Aqui você pode acionar uma API externa real, por exemplo:
        /*
        Http::post('http://seu-servidor-externo/sms', [
            'to' => $to,
            'message' => $message,
        ]);
        */

        return [
            'status' => 'simulado',
            'sid' => uniqid('fake_sid_'),
        ];
    }
}
