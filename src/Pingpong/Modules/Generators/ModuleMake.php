<?php

namespace Pingpong\Modules\Generators;

<<<<<<< HEAD
use Illuminate\Foundation\Application;
=======
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMake extends Command {

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
	protected $name = 'module:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate a new module.';

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
		$path = $this->app['module']->getPath();
=======
		$path = \Config::get('modules::module.path');
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
		
		$newModule = strtolower($this->argument('name'));
		$newModuleCaps = ucwords($newModule);

		if( is_dir($path.'/'.$newModule.'/'))
		{
			$this->error("Module $newModule already exists!");
			return;
		}

		$folders = array(
			'controllers',
			'models',
			'database',
			'database/migrations',
			'database/seeds',
			'config',
			'views',
			'lang',
			'tests',
		);

		/* Template */
<<<<<<< HEAD
		$configScript	= $this->app['files']->get(__DIR__.'/templates/module/config.txt');
		$filterScript	= $this->app['files']->get(__DIR__.'/templates/module/filters.txt');
		$routeTpl 		= $this->app['files']->get(__DIR__.'/templates/module/routes.txt');
		$controllerTpl 	= $this->app['files']->get(__DIR__.'/templates/module/controller.txt');
		$modelTpl 		= $this->app['files']->get(__DIR__.'/templates/module/model.txt');
		$viewTpl 		= $this->app['files']->get(__DIR__.'/templates/module/view.txt');
		$dbseederTpl	= $this->app['files']->get(__DIR__.'/templates/module/db-seeder.txt');
=======
		$configScript	= file_get_contents(__DIR__.'/templates/module/config.txt');
		$filterScript	= file_get_contents(__DIR__.'/templates/module/filters.txt');
		$routeTpl 		= file_get_contents(__DIR__.'/templates/module/routes.txt');
		$controllerTpl 	= file_get_contents(__DIR__.'/templates/module/controller.txt');
		$modelTpl 		= file_get_contents(__DIR__.'/templates/module/model.txt');
		$viewTpl 		= file_get_contents(__DIR__.'/templates/module/view.txt');
		$dbseederTpl	= file_get_contents(__DIR__.'/templates/module/db-seeder.txt');
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199

		/* replacing routes */
		$search = array(
			'{{lower-module}}',
			'{{module}}',
			'{{moduleCaps}}'
		);
		$replace = array(
			strtolower($newModule),
			$newModule,
			$newModuleCaps
		);
		$routeScript = str_replace($search, $replace, $routeTpl);

		/* replacing controller */
		$search = array(
			'{{module}}',
			'{{moduleCaps}}'
		);
		$replace = array(
			$newModule,
			$newModuleCaps
		);
		$controllerScript = str_replace($search, $replace, $controllerTpl);

		/* replacing models */
		$search = array(
			'{{module}}'
		);
		$replace = array(
			$newModuleCaps
		);
		$modelScript = str_replace($search, $replace, $modelTpl);

		/* replacing view */
		$search = array(
			'{{module}}'
		);
		$replace = array(
			$newModule
		);
		$viewScript = str_replace($search, $replace, $viewTpl);

		/* replacing db-seeder */
		$search = array(
			'{{module}}'
		);
		$replace = array(
			$newModuleCaps
		);
		$dbseederScript = str_replace($search, $replace, $dbseederTpl);

		//files will be created
		$files = array(
			'controllers/'.$newModuleCaps.'Controller.php' 	=> 	$controllerScript,
			'models/'.$newModuleCaps.'.php' 				=> 	$modelScript,
			'database/seeds/'.$newModuleCaps.'DatabaseSeeder.php'	=> 	$dbseederScript,
			'config/app.php' 								=> 	$configScript,
			'filters.php' 									=> 	$filterScript,
			'routes.php'									=>	$routeScript,
			'views/hello.blade.php'							=>	$viewScript
		);

		// creating new folder 
		foreach ($folders as $folder) {
<<<<<<< HEAD
			$newFolder = $path.$newModule.'/'.$folder.'/';
=======
			$newFolder = $path.'/'.$newModule.'/'.$folder.'/';
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
			if( ! mkdir($newFolder, 0755, true))
			{
				$this->error("Can not create folder : $newFolder");
			}else
			{
				$this->info("Created folder : $newFolder");
			}
		}

		//creating new file
		foreach ($files as $filename => $content) {
<<<<<<< HEAD
			$filename =  $path.$newModule.'/'.$filename;
=======
			$filename =  $path.'/'.$newModule.'/'.$filename;
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
			if( ! file_put_contents($filename, $content))
			{
				$this->error("Can not create : $filename");
			}else
			{
				$this->info("Created : $filename");
			}
		}

		$this->info("Module $newModule has been created. Enjoy!");
		$this->call('dump-autoload', array());
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'Module name.'),
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
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
