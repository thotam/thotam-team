<?php

namespace Thotam\ThotamTeam;

use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Thotam\ThotamTeam\Http\Livewire\TeamLivewire;

class ThotamTeamServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'thotam-team');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'thotam-team');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Route::domain('beta.' . env('APP_DOMAIN', 'cpc1hn.com.vn'))->group(function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('thotam-team.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/thotam-team'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/thotam-team'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/thotam-team'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }

        /*
        |--------------------------------------------------------------------------
        | Seed Service Provider need on boot() method
        |--------------------------------------------------------------------------
        */
        $this->app->register(SeedServiceProvider::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'thotam-team');

        // Register the main class to use with the facade
        $this->app->singleton('thotam-team', function () {
            return new ThotamTeam;
        });

        if (class_exists(Livewire::class)) {
            Livewire::component('thotam-team::team-livewire', TeamLivewire::class);
        }
    }
}
