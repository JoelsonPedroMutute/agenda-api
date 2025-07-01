<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Agenda API",
 *     version="1.0",
 *     description="Documentação da API de agendamentos e lembretes"
 * )

 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor local de desenvolvimento"
 * )

 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Insira o token de autenticação Bearer aqui"
 * )
 */
class SwaggerInfo {}
