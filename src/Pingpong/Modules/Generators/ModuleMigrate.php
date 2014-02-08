<?php

namespace Pingpong\Modules\Generators;

<<<<<<< HEAD
use Illuminate\Foundation\Application;
=======
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

// use Config;

class ModuleMigrate extends Command {

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
	protected $name = 'module:migrate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Migrate from specified module.';

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
		$module = strtolower($this->option('module'));

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
=======
		$name = strtolower($this->argument('name'));

		$path = \Config::get('modules::module.directory');
		$path.= '/'.$name.'/database/migrations';

		$this->info("Migrating from module $name.");

		$this->call('migrate', array(
				'--path'	=>	$path
			)
		);
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
<<<<<<< HEAD
=======
			array('name', InputArgument::REQUIRED, 'Module name.'),
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
		);
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
