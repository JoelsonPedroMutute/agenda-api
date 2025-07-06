<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Renderiza exceções como resposta HTTP personalizada.
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $model = class_basename($e->getModel());

            return response()->json([
                'success' => false,
                'message' => 'Recurso não encontrado.',
                'erro' => "$model não encontrado com o ID informado."
            ], Response::HTTP_NOT_FOUND);
        }

        return parent::render($request, $e);
    }

    public function register(): void
    {
        // Nada aqui interfere, o render já cuida do tratamento
    }
}
