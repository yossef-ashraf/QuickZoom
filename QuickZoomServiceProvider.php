<?php

namespace YourVendor\QuickZoom;

use Illuminate\Support\ServiceProvider;
use YourVendor\QuickZoom\Services\ZoomService;

class QuickZoomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/quickzoom.php', 'quickzoom'
        );

        $this->app->singleton('quickzoom', function ($app) {
            return new ZoomService(
                config('quickzoom.api_key'),
                config('quickzoom.api_secret'),
                config('quickzoom.base_url')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/quickzoom.php' => config_path('quickzoom.php'),
        ], 'quickzoom-config');
    }
}