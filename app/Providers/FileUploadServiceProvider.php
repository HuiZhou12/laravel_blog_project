<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FileUploadService;

class FileUploadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FileUploadService::class, function ($app) {
            return new FileUploadService();
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
