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
    // Bloquear usuários soft-deletados ou inexistentes
    Sanctum::authenticateAccessTokensUsing(function ($accessToken, $isValid) {
        $user = $accessToken->tokenable;

        return $isValid && $user && is_null($user->deleted_at);
    });
}
}