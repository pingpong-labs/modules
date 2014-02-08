<?php

namespace Pingpong\Modules\Generators;

use Illuminate\Foundation\Application;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMake extends Command {

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
	protected $name = 'module:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate a new module.';

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
		$path = $this->app['module']->getPath();
		
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
		$phpunitScript  = $this->app['files']->get(__DIR__.'/templates/module/phpunit.txt');
		$configScript	= $this->app['files']->get(__DIR__.'/templates/module/config.txt');
		$filterScript	= $this->app['files']->get(__DIR__.'/templates/module/filters.txt');
		$routeTpl 		= $this->app['files']->get(__DIR__.'/templates/module/routes.txt');
		$controllerTpl 	= $this->app['files']->get(__DIR__.'/templates/module/controller.txt');
		$modelTpl 		= $this->app['files']->get(__DIR__.'/templates/module/model.txt');
		$viewTpl 		= $this->app['files']->get(__DIR__.'/templates/module/view.txt');
		$dbseederTpl	= $this->app['files']->get(__DIR__.'/templates/module/db-seeder.txt');

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
			'views/hello.blade.php'							=>	$viewScript,
			'phpunit.xml'									=>	$phpunitScript
		);

		// creating new folder 
		foreach ($folders as $folder) {
			$newFolder = $path.$newModule.'/'.$folder.'/';
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
			$filename =  $path.$newModule.'/'.$filename;
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
		return array();
	}

}
