<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Agenda API",
 *     description="Documentação da API de Agendamentos"
 * )
 *
 * @OA\Server(
 *     url="http://agenda-api.test",
 *     description="Servidor local"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Autenticação via token Bearer (Laravel Sanctum)"
 * )
 */
class OpenApi
{
}
