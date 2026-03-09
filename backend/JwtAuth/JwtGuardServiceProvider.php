<?php

namespace JwtAuth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class JwtGuardServiceProvider extends ServiceProvider
{


    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::extend('jwt',function($app,$name,$config){
            return new JwtGuard(
                provider:Auth::createUserProvider($config['provider']),
                jwt: new JwtProvider(new Base64UrlEncoder()),
                request:$app['request']
            );
        });
    }
}
