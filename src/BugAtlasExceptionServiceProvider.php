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
        // Bind the BugAtlasExceptionHandlingService to the ExceptionHandler interface
        $this->app->bind('Illuminate\Contracts\Debug\ExceptionHandler', function () {
            // Return a new instance of BugAtlasExceptionHandlingService, injecting a new BugAtlasReporterService instance
            return new BugAtlasExceptionHandlingService(new BugAtlasReporterService());
        });
    }
}
