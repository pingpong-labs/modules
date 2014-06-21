<?php namespace Pingpong\Modules\Commands;

use Pingpong\Modules\Module;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModulePublisherCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:publish';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Publish assets from the specified modules or from all modules.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Module $module, File $files)
	{
		$this->module 	= $module;
		$this->files 	= $files;
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$moduleName = ucwords($this->argument('module'));
		if( ! $moduleName )
		{
			foreach ($this->module->all() as $module) {
				$this->publish($module);
			}
			return $this->info("All assets from all modules has been published successfully.");
		}
		if($this->module->exists($moduleName))
		{
			$this->publish($moduleName);
			return $this->info("Assets from module [$moduleName] has been published successfully.");
		}
		return $this->info("Module [$moduleName] does not exists.");
	}

	/**
	 * Get assets path for the specified module.
	 *
	 * @param  string  $name
	 * @return string
	 */
	protected function getAssetsPath($name)
	{
		return realpath($this->module->getPath() . "/$name/assets/");
	}

	/**
	 * Get destination assets path for the specified module.
	 *
	 * @param  string  $name
	 * @return string
	 */
	public function getDestinationPath($name)
	{
		return realpath($this->module->getAssetsPath()) . "/$name/";
	}

	/**
	 * Publish assets from the specified module.
	 *
	 * @param  string  $name
	 * @return void
	 */
	protected function publish($name)
	{
		$folder = $this->getAssetsPath($name);
		$dest 	= strtolower($this->getDestinationPath($name));
		$this->files->copyDirectory($folder, $dest);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
		);
	}

}
