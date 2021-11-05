<?php

declare(strict_types=1);

namespace OpiyOrg\LaravelSanitized;

use Illuminate\Support\ServiceProvider;

/**
 * LaravelSanitizedServiceProvider
 */
class LaravelSanitizedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/purify.php' => config_path('purify.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/purify.php', 'purify');
    }
}
