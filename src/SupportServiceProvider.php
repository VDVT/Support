<?php

namespace VDVT\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use VDVT\Support\Utils\MailVariable;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publishing is only necessary when using the CLI.
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'vdvt/support');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'vdvt/support');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/support.php' => config_path('vdvt/support/support.php'),
        ], 'vdvt');

        // Publishing the view files.
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/vdvt/support'),
        ], 'vdvt');

        // Publishing the translation files.
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/vdvt/support'),
        ], 'vdvt');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        foreach (File::glob(__DIR__ . '/../helpers/*.php') as $helper) {
            File::requireOnce($helper);
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/support.php', 'vdvt.support.support');

        // Register the service the package provides.
        $this->app->singleton('MailVariable', function () {
            return new MailVariable;
        });

        $this->app->singleton('IOCService', function ($app) {
            $service = config('vdvt.support.support.ioc_service_provider');
            return new $service($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['MailVariable', 'IOCService'];
    }
}
