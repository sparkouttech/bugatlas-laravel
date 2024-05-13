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
        // Check if any of the required configuration values for BugAtlas integration are missing or empty.
        if (empty(config('bugatlas.api_key')) || empty(config('bugatlas.secret_key')) || empty(config('bugatlas.tag'))) {
            return; //exit from a function
        }
        
        // Bind the BugAtlasExceptionHandlingService to the ExceptionHandler interface
        $this->app->bind('Illuminate\Contracts\Debug\ExceptionHandler', function () {
            // Return a new instance of BugAtlasExceptionHandlingService, injecting a new BugAtlasReporterService instance
            return new BugAtlasExceptionHandlingService(new BugAtlasReporterService());
        });
    }
}
