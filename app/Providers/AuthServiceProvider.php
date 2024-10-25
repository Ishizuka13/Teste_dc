<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Model' => 'App\Policies\Modelpolicy'
    ];

    public function boot()
    {
        Passport::routes();

        // Tokens de acesso válidos por 1 dia
        Passport::tokensExpireIn(now()->addDays(1));

        // Tokens de refresh válidos por 30 dias
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
