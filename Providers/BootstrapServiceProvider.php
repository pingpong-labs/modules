<?php namespace Pingpong\Modules\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapServiceProvider extends ServiceProvider
{

    /**
     * Booting the package.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['modules']->register();
    
        $this->app['modules']->boot();
    }

    /**
     * Register the provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
