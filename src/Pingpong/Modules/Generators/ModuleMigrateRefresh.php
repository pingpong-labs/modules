<?php

namespace Pingpong\Modules\Generators;

use Illuminate\Foundation\Application;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMigrateRefresh extends Command {

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
	protected $name = 'module:migrate-refresh';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Reset database and run migration from specified module.';

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
		$database = $this->option('database');
		$module = strtolower($this->argument('module'));

		$path = $this->app['module']->getDirName();
		$path.= '/'.$module.'/database/migrations';

		if(!is_dir($path))
		{
			$this->error("Module $module not exists.");
			return;
		}

		$this->info("Migrating from module $module.");

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
			array('module', InputArgument::REQUIRED, 'Module name.'),
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
