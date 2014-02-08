<?php

namespace Pingpong\Modules\Generators;

<<<<<<< HEAD
use Illuminate\Foundation\Application;
=======
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleControllerMake extends Command {

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
	protected $name = 'module:controller-make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new restful controller for specified module.';

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
		$module 	= strtolower($this->argument('module'));
		$controller = $this->argument('controller');

<<<<<<< HEAD
		$path = $this->app['module']->getPath() . $module . '/controllers/';
		if(!is_dir($path))
		{
			$this->error("Module $module dest not exists.");
			return;
		}
		$template = $this->app['files']->get(__DIR__.'/templates/controller.txt');
=======
		$path = \Config::get('modules::module.path').'/'.$module.'/controllers/';
		if(!is_dir($path))
		{
			$this->error("Module $module not exists.");
			return;
		}
		$template = file_get_contents(__DIR__.'/templates/controller.txt');
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
		$search = array(
			'{{controller}}',
			'{{module}}'	
		);
		$replace = array(
			ucwords($controller),
			ucwords($module),
		);

		$controllerName = $path . ucwords($controller).'Controller.php';

		// script
		$script = str_replace($search, $replace, $template);

<<<<<<< HEAD
		if( ! $this->app['files']->put($controllerName, $script))
=======
		if( ! file_put_contents($controllerName, $script))
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
		{
			$this->error('Can not create controller!');
		}
		else{
			$this->info('Controller created successfully.');
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
			array('module', InputArgument::REQUIRED, 'Module name.'),
			array('controller', InputArgument::REQUIRED, 'Controller name.'),
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
