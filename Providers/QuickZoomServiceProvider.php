<?php
namespace QuickZoom\Providers;

use Illuminate\Support\ServiceProvider;
use QuickZoom\Console\Commands\InstallQuickZoomCommand;
use QuickZoom\Contracts\ZoomServiceInterface;
use QuickZoom\Services\ZoomService;

class QuickZoomServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/quickzoom.php', 'quickzoom');
        
        $this->app->singleton(ZoomServiceInterface::class, function ($app) {
            return new ZoomService();
        });

        $this->app->alias(ZoomServiceInterface::class, 'quickzoom');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/quickzoom.php' => config_path('quickzoom.php'),
        ], 'quickzoom-config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'quickzoom-migrations');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->commands([InstallQuickZoomCommand::class]);
        }
    }
}