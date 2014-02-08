<?php

namespace Pingpong\Modules\Generators;

use Illuminate\Foundation\Application;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleSeeder extends Command {

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
	protected $name = 'module:seed';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Seed from specified module.';

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
		$module = $module = $this->option('module');
		
		if(empty($module))
		{
			foreach ($this->app['module']->all() as $moduleName)
			{
				$this->seed($moduleName);
				$this->info("All seeder has been successfully seed.");
			}
		}else
		{
			$this->seed($module);
		}	
	}

	/**
	 * Seed from specified module.
	 *
	 * @return void
	 */
	protected function seed($module)
	{
		$path = $this->app['module']->getPath();
		$path.= $module.'/database/seeds/';

		if(!is_dir($path))
		{
			$this->error("Module $path not exists.");
			return;
		}

		$class = ucwords($module) . 'DatabaseSeeder';

		if(class_exists($class))
		{
			$this->info("Seeding from module $module.");

			$this->call('db:seed', array(
					'--class'	=>	$class
				)
			);	
		}else
		{
			$this->error("Class 'DatabaseSeeder' on module [$module] not exists.");
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('--module', null, InputOption::VALUE_OPTIONAL, 'Module name or type all for migrate from all modules.', null),
		);
	}

}
