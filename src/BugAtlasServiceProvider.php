<?php

namespace Sparkouttech\BugAtlas;

use Illuminate\Support\ServiceProvider;

class BugAtlasServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/bugatlas.php' => config_path('bugatlas.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/bugatlas.php', 'bugatlas');

        // Register the main class to use with the facade
        $this->app->singleton('bug-atlas', function () {
            return new BugAtlas;
        });
    }
}
