<?php

namespace Sparkouttech\BugAtlas;

use Illuminate\Support\ServiceProvider;
use Sparkouttech\BugAtlas\Services\ExceptionHandlingService;
use Sparkouttech\BugAtlas\Services\BugAtlasReporterService;

class ExceptionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind('Illuminate\Contracts\Debug\ExceptionHandler', function ($app) {
            return new ExceptionHandlingService($app, new BugAtlasReporterService());
        });
    }
}
