<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Markdowner;

class MarkdownerProvide extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Markdowner::class, function($app){
            return new Markdowner();
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
