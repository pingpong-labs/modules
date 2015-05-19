<?php namespace Pingpong\Modules;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Pingpong\Support\Stub;
use Pingpong\Modules\Commands;

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
     *
     * @return void
     */
    public function boot()
    {
        $this->registerNamespaces();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
    }

    /**
     * Setup stub path.
     *
     * @return void
     */
    public function setupStubPath()
    {
        $this->app->booted(function ($app) {
            Stub::setBasePath(__DIR__ . '/Commands/stubs');

            if ($app['modules']->config('stubs.enabled') === true) {
                Stub::setBasePath($app['modules']->config('stubs.path'));
            }
        });
    }

    /**
     * Register package's namespaces.
     *
     * @return void
     */
    protected function registerNamespaces()
    {
        $configPath = __DIR__ . '/src/config/config.php';
        $this->mergeConfigFrom($configPath, 'modules');
        $this->publishes([$configPath => config_path('modules.php')]);
    }

    /**
     * Register laravel html package.
     *
     * @return void
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
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->bindShared('modules', function ($app) {
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
     *
     * @return void
     */
    protected function registerProviders()
    {
        $this->app->register(__NAMESPACE__ . '\\Providers\\ConsoleServiceProvider');
        $this->app->register('Pingpong\Modules\Providers\ContractsServiceProvider');
    }
}
