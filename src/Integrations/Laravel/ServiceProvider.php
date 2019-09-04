<?php

/** @noinspection PhpUndefinedFunctionInspection */

namespace Oilstone\ActOn\Integrations\Laravel;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Oilstone\ActOn\ActOn;

/**
 * Class ServiceProvider
 * @package Oilstone\ActOn\Integrations\Laravel
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../../../config/acton.php';

        $this->mergeConfigFrom($configPath, 'acton');

        $this->app->singleton('Oilstone\ActOn\ActOn', function () {
            return new ActOn(config('acton'));
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../../../config/acton.php';

        $this->publishes([$configPath => $this->getConfigPath()], 'config');
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('acton.php');
    }

    /**
     * Publish the config file
     *
     * @param string $configPath
     */
    protected function publishConfig($configPath)
    {
        $this->publishes([$configPath => config_path('acton.php')], 'config');
    }
}