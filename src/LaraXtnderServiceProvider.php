<?php

namespace IasonArgyrakis\LaraXtnder;

use Illuminate\Support\ServiceProvider;

class LaraXtnderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'lara-xtnder');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'lara-xtnder');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('lara-xtnder.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/lara-xtnder'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/lara-xtnder'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/lara-xtnder'),
            ], 'lang');*/

            // Registering package commands.
            $this->registerConsoleCommands();

        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'lara-xtnder');

        // Register the main class to use with the facade
        $this->app->singleton('lara-xtnder', function () {
            return new LaraXtnder;
        });
    }

    public function registerConsoleCommands(){
        $this->commands([
            Commands\ExtendApiRoutesCommand::class,
            Commands\ExtendedBase::class,
            Commands\ExtendFactoryCommand::class,
            Commands\ExtendMigrationCommand::class,
            Commands\ExtendStoreModelRequestCommand::class,
            Commands\ExtendUpdateModelRequestCommand::class,
            Commands\ExtendWebBase::class,
            Commands\ExtendWebRoutesCommand::class
        ]);
    }
}
