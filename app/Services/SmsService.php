<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected Client $client;
    protected string $from;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );

        $this->from = config('services.twilio.from'); // número de envio da Twilio
    }

    /**
     * Envia uma mensagem SMS para um número, se for permitido.
     *
     * @param string $to Número do destinatário
     * @param string $message Conteúdo da mensagem
     * @return array Resultado do envio (status/sid ou erro)
     */
    public function send(string $to, string $message): array
    {
        // 🚫 Adicione aqui os números permitidos (verificados)
        $verificados = [
            '+244976173722',  // Exemplo: Joelson
            '+24491316XXXX',  // Substitua por outros verificados se tiver
            '+244913164583',
            '+244988463625',
            '+244982065374',
            '+244946621300',
            '+244939186397',
            '+244995435260',
            '+244912113816',
            '+244922275719',
            '+244973935496',
            '+244929459210',
            '+244957969259',
            '+244946846266',
            '+244910436737',
            '+244969508632',
            '++244993073769',
            '+244981984854',
            '+244945921560',
            '+244973459241',
            '+244949769811',
            '+244974393316',
            '+244999695139',
            '+244949437441',
            '+244985289559',
            '+244933381679',
            '+244974157516',
            '+244968579041',
            '+244995224905',
            '+244995143724',
            '+244942153390',
            '+244913164583',
            '+244981998362',
            '+244998069007',
            '+244913164583',


        ];

        if (!in_array($to, $verificados)) {
            Log::warning("Número não verificado com conta Twilio Trial: {$to}");
            return [
                'status' => 'não enviado',
                'sid'    => null,
            ];
        }

        try {
            $message = $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message,
            ]);

            return [
                'status' => $message->status,
                'sid'    => $message->sid,
            ];
        } catch (\Exception $e) {
            Log::error("Erro ao enviar SMS com Twilio: " . $e->getMessage());

            return [
                'status' => 'erro',
                'sid'    => null,
            ];
        }
    }
}
