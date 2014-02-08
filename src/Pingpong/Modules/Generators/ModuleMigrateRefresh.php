<?php

namespace Pingpong\Modules\Generators;

<<<<<<< HEAD
use Illuminate\Foundation\Application;
=======
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMigrateRefresh extends Command {

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
	protected $name = 'module:migrate-refresh';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Reset database and run migration from specified module.';

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
		$database = $this->option('database');
<<<<<<< HEAD
		$module = strtolower($this->argument('module'));

		$path = $this->app['module']->getDirName();
		$path.= '/'.$module.'/database/migrations';

		if(!is_dir($path))
		{
			$this->error("Module $module not exists.");
			return;
		}

		$this->info("Migrating from module $module.");
=======

		$name = strtolower($this->argument('name'));
		// $name = ucwords($name);

		$path = \Config::get('modules::module.directory');
		$path.= '/'.$name.'/database/migrations';

		if(!is_dir($path))
		{
			$this->error("Module $name not exists.");
			return;
		}

		$this->info("Migrating from module $name.");
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199

		$parameters = array();
		if(! empty($database))
		{
			$parameters['--database'] = $database;
		}
		$this->call('migrate:reset', $parameters);

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
<<<<<<< HEAD
			array('module', InputArgument::REQUIRED, 'Module name.'),
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
			array('--database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.', null),
		);
	}

}
