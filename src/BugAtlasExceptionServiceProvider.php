<?php

namespace Sparkouttech\BugAtlas;

use Illuminate\Support\ServiceProvider;
use Sparkouttech\BugAtlas\Services\BugAtlasExceptionHandlingService;
use Sparkouttech\BugAtlas\Services\BugAtlasReporterService;

class BugAtlasExceptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind('Illuminate\Contracts\Debug\ExceptionHandler', function ($app) {
            return new BugAtlasExceptionHandlingService($app, new BugAtlasReporterService());
        });
    }
}
