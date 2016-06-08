<?php

namespace App\Applications\User\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Applications\User\Http\Controllers';

    public function boot(Router $router)
    {
        $this->registerRoutes($this->app['router']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    protected function registerRoutes(Router $router)
    {
        $router->group(['namespace' => $this->namespace, 'prefix' => env('API_VERSION_PREFIX', 'v1')], function ($router) {
            require app_path('Applications/User/Http/routes.php');
        });
    }
}
