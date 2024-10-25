<?php

namespace Ongoing\Sucursales;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class SucursalesBaseServiceProvider extends ServiceProvider {

    public function boot(){
        $this->registerResources();
    }

    public function register(){
        $this->commands([
            // Console\SucursalesInitCommand::class
        ]);
    }

    private function registerResources(){
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sucursales');

        $this->registerRoutes();
    }

    protected function registerRoutes(){
        Route::group(["prefix" => "sucursales"], function(){
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });

        Route::group([
            "prefix" => "api",
            "middleware" => ['api']
        ], function(){
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }
}