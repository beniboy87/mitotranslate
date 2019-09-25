<?php

namespace Balazsbencs\Translate;

use Illuminate\Support\ServiceProvider;
use Balazsbencs\Translate\Console\Commands\RefreshTranslation;

class TranslateServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/translate.php', 'translate'
        );

        // Register the service the package provides.
        $this->app->singleton('translate', function ($app) {
            return new Translate;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['translate'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/translate.php' => config_path('translate.php'),
        ], 'config');

        // Registering package commands.
        $this->commands([
            RefreshTranslation::class
        ]);

    }
}
