<?php

namespace Pingpong\Modules\Generators;

use Illuminate\Console\Command;
<<<<<<< HEAD
use Illuminate\Foundation\Application;
=======
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleSetup extends Command {

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
	protected $name = 'module:setup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Setup module and publish configuration from this package.';

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
<<<<<<< HEAD
	{				
		$folder = $this->app['module']->getPath();
=======
	{
		// packagist
		$this->call('config:publish', array('package'	=>	'pingpong/modules'));
				
		$folder = \Config::get('modules::module.path').'/';
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
		if( is_dir($folder))
		{
			$this->error("Module already setup!");
		}else
		{
			if( ! mkdir($folder, 0775, true))
			{
				$this->error('Can not setup module. Is your root directory writable?');
			}else{
				$this->info('Module setup successfully.');
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
