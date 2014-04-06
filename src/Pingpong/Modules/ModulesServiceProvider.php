<?php namespace Pingpong\Modules;

use Pingpong\Modules\Commands;
use Illuminate\Support\ServiceProvider;

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
	 * @return 	void
	 */
	public function boot()
	{
		$this->package('pingpong/modules');
		$this->registerAutoloader();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerServices();
		$this->registerCommands();
	}

	/**
	 * Register the autoloader.
	 *
	 * @return void
	 */
	public function registerAutoloader()
	{
		$this->app['modules']->register();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	protected function registerServices()
	{		
		$this->app['modules.finder'] = $this->app->share(function($app)
		{
			return new ModuleFinder($app);
		});
		$this->app['modules'] = $this->app->share(function($app)
		{
			return new Module($app, $app['modules.finder']);
		});
	}

	/**
	 * Register the commands.
	 *
	 * @return void
	 */
	protected function registerCommands()
	{
		$this->app['modules.controller'] = $this->app->share(function($app)
		{
			return new Commands\ModuleControllerCommand($app['modules']);
		});
		$this->app['modules.model'] = $this->app->share(function($app)
		{
			return new Commands\ModuleModelCommand($app['modules'], $app['files']);
		});
		$this->app['modules.publisher'] = $this->app->share(function($app)
		{
			return new Commands\ModulePublisherCommand($app['modules'], $app['files']);
		});
		$this->app['modules.setup'] = $this->app->share(function($app)
		{
			return new Commands\ModuleSetupCommand($app['modules'], $app['files']);
		});
		$this->app['modules.maker'] = $this->app->share(function($app)
		{
			return new Commands\ModuleMakeCommand($app['modules'], $app['files']);
		});
		$this->app['modules.seed-maker'] = $this->app->share(function($app)
		{
			return new Commands\ModuleSeedMakeCommand($app['modules'], $app['files']);
		});
		$this->app['modules.seeder'] = $this->app->share(function($app)
		{
			return new Commands\ModuleSeedCommand($app['modules'], $app['files']);
		});
		$this->app['modules.migrator'] = $this->app->share(function($app)
		{
			return new Commands\ModuleMigrateCommand($app['modules']);
		});
		$this->app['modules.migration-maker'] = $this->app->share(function($app)
		{
			return new Commands\ModuleMigrateMakeCommand($app['modules'], $app['files']);
		});
		$this->app['modules.command-maker'] = $this->app->share(function($app)
		{
			return new Commands\ModuleCommandCommand($app['modules']);
		});
		$this->commands(
			'modules.controller',
			'modules.model',
			'modules.publisher',
			'modules.setup',
			'modules.maker',
			'modules.seed-maker',
			'modules.seeder',
			'modules.migrator',
			'modules.migration-maker',
			'modules.command-maker'
		);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('modules', 'modules.finder');
	}

}
