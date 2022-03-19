<?php

namespace Neiderruiz\SentryNotificationLaravel\Providers;


use Illuminate\Support\ServiceProvider;

class SentryNotificationLaravelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../slack-config.php',
            'config.slack'
        );
    }

    protected function offerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../slack-config.php' => config_path('slack-config.php'),
        ], 'config');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->offerPublishing();
    }
}
