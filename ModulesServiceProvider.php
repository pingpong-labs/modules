<?php

namespace Pingpong\Modules;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Pingpong\Support\Stub;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->registerNamespaces();
    }

    /**
     * Register all modules.
     */
    protected function registerModules()
    {
        $this->app->register('Pingpong\Modules\Providers\BootstrapServiceProvider');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
        $this->registerModules();
    }

    /**
     * Setup stub path.
     */
    public function setupStubPath()
    {
        $this->app->booted(function ($app) {
            Stub::setBasePath(__DIR__.'/Commands/stubs');

            if ($app['modules']->config('stubs.enabled') === true) {
                Stub::setBasePath($app['modules']->config('stubs.path'));
            }
        });
    }

    /**
     * Register package's namespaces.
     */
    protected function registerNamespaces()
    {
        $configPath = __DIR__.'/src/config/config.php';
        $this->mergeConfigFrom($configPath, 'modules');
        $this->publishes([
            $configPath => config_path('modules.php')
        ], 'config');
    }

    /**
     * Register laravel html package.
     */
    protected function registerHtml()
    {
        $this->app->register('Collective\Html\HtmlServiceProvider');

        $aliases = [
            'HTML' => 'Collective\Html\HtmlFacade',
            'Form' => 'Collective\Html\FormFacade',
            'Module' => 'Pingpong\Modules\Facades\Module',
        ];

        AliasLoader::getInstance($aliases)->register();
    }

    /**
     * Register the service provider.
     */
    protected function registerServices()
    {
        $this->app->singleton('modules', function ($app) {
            $path = $app['config']->get('modules.paths.modules');

            return new Repository($app, $path);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('modules');
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        $this->app->register(__NAMESPACE__.'\\Providers\\ConsoleServiceProvider');
        $this->app->register('Pingpong\Modules\Providers\ContractsServiceProvider');
    }
}
