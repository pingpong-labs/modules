<?php namespace Pingpong\Modules\Providers;

use Illuminate\Support\ServiceProvider;

class ContractsServiceProvider extends ServiceProvider
{

    /**
     * Register some binding.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Pingpong\Modules\Contracts\RepositoryInterface',
            'Pingpong\Modules\Repository'
        );
    }
}
