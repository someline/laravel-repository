<?php

namespace Someline\Repository\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelRepositoryServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../config/config.php' => config_path('repository.php'),
        ]);
        $this->mergeConfigFrom(__DIR__ . '/../../../config/config.php', 'repository');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands('Someline\Repository\Providers\Generators\Commands\RepositoryCommand');
        $this->commands('Someline\Repository\Providers\Generators\Commands\TransformerCommand');
        $this->commands('Someline\Repository\Providers\Generators\Commands\EntityCommand');
        $this->commands('Someline\Repository\Providers\Generators\Commands\ControllerCommand');
    }

    public static function getConfig($name, $default = null)
    {
        return config('repository.' . $name, $default);
    }

}
