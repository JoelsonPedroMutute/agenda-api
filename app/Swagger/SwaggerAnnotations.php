<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Agenda API",
 *     version="1.0",
 *     description="Documentação da API de agendamentos e lembretes"
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor principal" 
 * )
 */
class SwaggerInfo {}
