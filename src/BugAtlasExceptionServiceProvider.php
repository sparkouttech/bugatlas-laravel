<?php

namespace Sparkouttech\BugAtlas;

use Illuminate\Support\ServiceProvider;
use Sparkouttech\BugAtlas\Services\BugAtlasExceptionHandlingService;
use Sparkouttech\BugAtlas\Services\BugAtlasReporterService;

class BugAtlasExceptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * Checks for BugAtlas configuration and binds BugAtlasExceptionHandlingService
     * to Illuminate\Contracts\Debug\ExceptionHandler if configuration is present.
     */
    public function boot(): void
    {
        if (empty(config('bugatlas.api_key')) || empty(config('bugatlas.secret_key')) || empty(config('bugatlas.tag'))) {
            return;
        }
        
        $this->app->bind('Illuminate\Contracts\Debug\ExceptionHandler', function () {
            return new BugAtlasExceptionHandlingService(new BugAtlasReporterService());
        });
    }
}
