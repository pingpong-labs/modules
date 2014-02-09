<?php namespace Pingpong\Modules\Generators;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleAssetPublish extends Command {

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
	protected $name = 'module:asset-publish';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Publish assets from specified module or from all modules.';

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
				$this->publishAsset($module);
			}
			$this->info('All assets has been published.');
		}else
		{
			$this->publishAsset($module);
		}
	}

	/**
	 * Do publishing.
	 *
	 * @return void
	 */
	protected function publishAsset($module)
	{
		if( ! $this->app['module']->has($module))
		{
			$this->error("Module [$module] doest not exists.");
		}else
		{
			$assets = $this->app['module']->getPath($module) . 'assets';
			$target = $this->app['module']->getAssetPath($module);

			$publish = $this->app['files']->copyDirectory($assets, $target);
			if ( ! $publish)
			{
				$this->error("Can not publish assets from module [$module]. Is your publish directory writable?");
			}else
			{
				$this->info("Assets from module [$module] has been published.");	
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
