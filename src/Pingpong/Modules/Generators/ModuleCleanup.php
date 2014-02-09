<?php namespace Pingpong\Modules\Generators;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleCleanup extends Command {

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
	protected $name = 'module:cleanup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Remove all assets folder form specified modules or all modules.';

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
		$module = $this->argument('module');
		if(empty($module))
		{
			foreach ($this->app['module']->all() as $module) {
				$this->cleanup($module);
			}
			$this->info('Cleaning up from all modules successfully.');
		}else
		{
			$this->cleanup($module);
		}
	}

	/**
	 * Do cleaning up.
	 *
	 * @return void
	 */
	protected function cleanup($module)
	{
		if( ! $this->app['module']->has($module))
		{
			$this->error("Module [$module] doest not exists.");
		}else
		{
			$assets = $this->app['module']->getPath($module) . 'assets';

			$cleanup = $this->app['files']->deleteDirectory($assets);
			if ( ! $cleanup)
			{
				$this->error("Module [$module] doest not have 'assets' folder.");
			}else
			{
				$this->info("Cleaning up from module [$module] successfully.");	
			}
		}
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
