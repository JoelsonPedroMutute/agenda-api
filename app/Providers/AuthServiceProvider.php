<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Bloquear usuários soft-deletados de usarem tokens ativos
        Sanctum::authenticateAccessTokensUsing(function ($accessToken, $isValid) {
            return $isValid && is_null($accessToken->tokenable->deleted_at);
        });
    }
}
