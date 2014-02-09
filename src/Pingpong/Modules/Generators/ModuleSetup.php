<?php

namespace Pingpong\Modules\Generators;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleSetup extends Command {

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
	protected $name = 'module:setup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Setup module and publish configuration from this package.';

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
		$modulePath = $this->app['module']->getPath();
		$assetPath  = $this->app['module']->getAssetPath();

		// creating modules folder
		if( is_dir($modulePath))
		{
			$this->error("Module already setup!");
		}else
		{
			if( ! mkdir($modulePath, 0775, true))
			{
				$this->error('Can not setup module. Is your root directory writable?');
			}else{
				$this->info('Module directory setup successfully.');
			}	
		}
		
		// creating assets module folder on public path.
		if( is_dir($assetPath))
		{
			$this->error("Module already setup!");
		}else
		{

			if( ! mkdir($assetPath, 0775, true))
			{
				$this->error('Can not setup module. Is your root directory writable?');
			}else{
				$this->info('Module assets directory setup successfully.');
			}
		}
		$this->call('config:publish', array('package' => 'pingpong/modules'));
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
		return array();
	}

}
