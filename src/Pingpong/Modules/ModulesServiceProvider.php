<?php namespace Pingpong\Modules;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Pingpong\Generators\Stub;
use Pingpong\Modules\Commands;

class ModulesServiceProvider extends ServiceProvider {

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
        $this->package('pingpong/modules');

        $this->app['modules']->boot();

        $this->app['modules']->register();

        Stub::setPath(__DIR__ . '/Commands/stubs');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServices();
        $this->registerProviders();
    }

    /**
     * Register laravel html package.
     *
     * @return void
     */
    protected function registerHtml()
    {
        $this->app->register('Illuminate\Html\HtmlServiceProvider');

        $aliases = [
            'HTML' => 'Illuminate\Support\Facades\HTML',
            'Form' => 'Illuminate\Support\Facades\Form',
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
        $this->app->bindShared('modules', function ($app)
        {
            $path = $app['config']->get('modules::paths.modules');

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
    }
}
