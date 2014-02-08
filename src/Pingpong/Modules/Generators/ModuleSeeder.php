<?php

namespace Pingpong\Modules\Generators;

<<<<<<< HEAD
use Illuminate\Foundation\Application;
=======
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleSeeder extends Command {

	/**
<<<<<<< HEAD
	 * Application object
	 * 
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;
	
	/**
=======
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
	 * The console command name.
	 *
	 * @var string
	 */
<<<<<<< HEAD
	protected $name = 'module:seed';
=======
	protected $name = 'module:db-seed';
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Seed from specified module.';

	/**
	 * Create a new command instance.
<<<<<<< HEAD
	 * 
	 * @param 	$app 	Illuminate\Foundation\Application
	 * @return 	void
	 */
	public function __construct(Application $app)
	{
		parent::__construct();
		$this->app = $app;
=======
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
<<<<<<< HEAD
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
	public function seed($module)
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
=======
		$name = $module = $this->argument('name');
		$name = ucwords($name);

		$path = \Config::get('modules::module.directory');
		$path.= '/'.$module.'/database/seeds/';

		if(!is_dir($path))
		{
			$this->error("Module $module not exists.");
			return;
		}

		$class = $name . 'DatabaseSeeder';

		if(class_exists($class))
		{
			$this->info("Seeding from module $name.");
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199

			$this->call('db:seed', array(
					'--class'	=>	$class
				)
			);	
		}else
		{
<<<<<<< HEAD
			$this->error("Class 'DatabaseSeeder' on module [$module] not exists.");
=======
			$this->error("Class 'DatabaseSeeder' on module [$name] not exists.");
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
<<<<<<< HEAD
		return array();
=======
		return array(
			array('name', InputArgument::REQUIRED, 'Module name.'),
		);
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
<<<<<<< HEAD
			array('--module', null, InputOption::VALUE_OPTIONAL, 'Module name or type all for migrate from all modules.', null),
=======
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
		);
	}

}
