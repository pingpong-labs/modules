<?php

namespace Pingpong\Modules\Generators;

use Illuminate\Foundation\Application;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

// use Config;

class ModuleMigrate extends Command {

	/**
	 * Application object
	 * 
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;
	
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:migrate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Migrate from specified module or all modules.';

	/**
	 * Create a new command instance.
	 * 
	 * @param 	$app 	Illuminate\Foundation\Application
	 * @return 	void
	 */
	public function __construct(Application $app)
	{
		parent::__construct();
		$this->app = $app;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$module = strtolower($this->argument('module'));

		if(empty($module))
		{
			foreach ($this->app['module']->all() as $moduleName)
			{
				$this->migrate($moduleName);
				$this->info("All migrations has been successfully migrated.");
			}
		}else
		{
			$this->migrate($module);
		}		
	}

	/**
	 * Migrate from specified path.
	 *
	 * @return void
	 */
	protected function migrate($module)
	{
		$this->info("Migrating from module $module.");
		$moduleDirName = $this->app['module']->getDirName();
		$path = $moduleDirName . '/' . $module . '/database/migrations';
		$this->call('migrate', array(
				'--path'	=>	$path
			)
		);		
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('module', InputArgument::OPTIONAL, 'Module name.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
