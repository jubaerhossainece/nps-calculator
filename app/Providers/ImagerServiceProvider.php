<?php

namespace App\Providers;

use App\Services\ImageService;
use Illuminate\Support\ServiceProvider;

class ImagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ImageService', function($app){
            return new ImageService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
