<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Statamic\Statamic;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Needed for our IaC reverse proxy docker setup. Otherwise Laravel/Statamic would always act like it’s a `http` request,
        // because the request is passed to the app via http by traefik.
        if (App::environment(['staging', 'production'])) {
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Statamic::script('app', 'cp');
        if (App::environment(['local', 'staging', 'development'])) {
            Statamic::style('statamic/cp', 'cp-dev');
        }

        // Statamic::vite('app', [
        //     'resources/js/cp.js',
        //     'resources/css/cp.css',
        // ]);
    }
}
