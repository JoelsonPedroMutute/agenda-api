<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * Exceções que não serão reportadas.
     */
    protected $dontReport = [];

    /**
     * Campos sensíveis que não devem ser exibidos.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Registra os tratamentos customizados de exceções.
     */
    public function register(): void
    {
        // Trata casos de ModelNotFoundException (ex: ao buscar um modelo inexistente)
        $this->renderable(function (ModelNotFoundException $e, $request) {
            // Obtém o nome da classe do model
            $modelClass = class_basename($e->getModel());

            // Mapeia o nome da model para um nome mais amigável em português
            $modelMap = [
                'User' => 'Usuário',
                'Appointment' => 'Compromisso',
                'Reminder' => 'Lembrete',
                // Adicione mais se tiver outros models
            ];

            // Usa o nome traduzido, se existir no mapa
            $modelName = $modelMap[$modelClass] ?? $modelClass;

            return response()->json([
                'success' => false,
                'message' => 'Recurso não encontrado.',
                'erro' => "$modelName não encontrado com o ID informado."
            ], Response::HTTP_NOT_FOUND);
        });

        // Trata rotas inválidas (NotFoundHttpException)
        $this->renderable(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => 'Endpoint ou rota não encontrada.'
            ], Response::HTTP_NOT_FOUND);
        });
    }
}
