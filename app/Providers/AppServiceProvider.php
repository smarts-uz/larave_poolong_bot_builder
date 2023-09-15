<?php

namespace App\Providers;

use DebugBar\DebugBar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        $appAdress = $_SERVER;
        json_encode($appAdress);

        if (array_key_exists('HTTP_X_FORWARDED_PROTO',$appAdress)) {
            $this->app['request']->server->set('HTTPS', true);
        } else {
            $this->app['request']->server->set('HTTPS', false);
        }

        $this->app['request']->server->set('HTTPS', false);
    }
}
