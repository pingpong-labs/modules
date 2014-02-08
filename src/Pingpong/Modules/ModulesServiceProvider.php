<?php namespace Pingpong\Modules;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Application as Application;
use Pingpong\Modules\Module as Module;
use Pingpong\Modules\Generators as Generators;

class ModulesServiceProvider extends ServiceProvider {
	
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	
	/**
	 * Array providers.
	 *
	 * @var array
	 */
	protected $providers = array(
		'module'			=>	'Pingpong\Modules\Module',
		'module.collection'	=>	'Pingpong\Modules\Collection',
		'module.manifest'	=>	'Pingpong\Modules\Manifest',
	);
	
	/**
	 * Array facades.
	 *
	 * @var array
	 */
	protected $facades = array(
		'Module'	=>	'Pingpong\Modules\Facades\Module'
	);

	/**
	 * Booting the service provider.
	 *
	 * @return void
	 */

	public function boot()
	{
		$this->package('pingpong/modules', 'modules');
		$this->app['module.collection']->addNamespaces();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerProviders();
		$this->registerFacades();

		$this->registerCommands();	
		// register modules
		$this->app['module.collection']->register();		
	}
	
	/**
	 * Register all commands.
	 *
	 * @return void
	 */
	protected function registerCommands()
	{
		// register module:setup command
		$this->app['module.setup'] = $this->app->share(function($app)
		{
			return new Generators\ModuleSetup($app);
		});

		// register module:seeder command
		$this->app['module.seeder'] = $this->app->share(function($app)
		{
			return new Generators\ModuleSeeder($app);
		});

		// register module:refresh command
		$this->app['module.migrate-refresh'] = $this->app->share(function($app)
		{
			return new Generators\ModuleMigrateRefresh($app);
		});

		// register module:migrate-make command
		$this->app['module.migrate-make'] = $this->app->share(function($app)
		{
			return new Generators\ModuleMigrateMake($app);
		});

		// register module:migrate command
		$this->app['module.migrate'] = $this->app->share(function($app)
		{
			return new Generators\ModuleMigrate($app);
		});

		// register module:make command
		$this->app['module.make'] = $this->app->share(function($app)
		{
			return new Generators\ModuleMake($app);
		});

		// register module:controller command
		$this->app['module.controller'] = $this->app->share(function($app)
		{
			return new Generators\ModuleControllerMake($app);
		});

		// register all commands to application
		$this->commands(
			'module.make',
			'module.controller',
			'module.migrate',
			'module.migrate-make',
			'module.migrate-refresh',
			'module.seeder',
			'module.setup'
		);
	}
	/**
	 * Register all service providers.
	 *
	 * @return void
	 */
	protected function registerProviders()
	{
		$providers = $this->providers;
		foreach ($providers as $key => $value) {
			$this->app[$key] = $this->app->share(function($app) use ($value)
			{
				return new $value($app);
			});
		}
	}

	/**
	 * Register all facades.
	 *
	 * @return void
	 */
	protected function registerFacades()
	{
		$facades = $this->facades;
		$this->app->booting(function() use ($facades)
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			foreach ($facades as $key => $value) {
				$loader->alias($key, $value);
			}
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		$providers = array();
		foreach ($this->providers as $key => $value) {
			$providers[] = $key;
		}
		return $providers;
	}

}