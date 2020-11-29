<?php
namespace Ranta\CartApi;

use Freshbitsweb\LaravelCartManager\Contracts\CartDriver;
use Ranta\CartApi\Core\CartApi;
use Illuminate\Support\ServiceProvider;

class CartApiServiceProvider extends ServiceProvider{


    public function boot(){

        // $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // $this->publishes([
        //     __DIR__.'/config/cart_manager_api.php' => config_path('cart_manager_api.php'),
        // ]);
    }

    public function register(){


        // $this->mergeConfigFrom(
        //     __DIR__.'/config/cart_manager_api.php', 'cart_manager_api'
        // );

        // Bind the driver with contract
        $this->app->bind(CartDriver::class, \Ranta\CartApi\Drivers\DatabaseDriver::class);

        // Bind the cart class
        $this->app->bind(CartApi::class, function ($app) {
            return new CartApi($app->make(CartDriver::class));
        });


    }

}