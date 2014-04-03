<?php namespace Pingpong\Modules\Commands;

use Pingpong\Modules\Module;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleSetupCommand extends Command {

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
	protected $description = 'Setting up modules folders for first use.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Module $module, File $files)
	{
		$this->module = $module;
		$this->files  = $files;
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->setupModuleFolder();
		$this->setupModuleAssetsFolder();
	}

	/**
	 * Setup modules folder.
	 *
	 * @return mixed
	 */
	protected function setupModuleFolder()
	{
		if( ! $this->hasModulePath())
		{
			$this->files->makeDirectory($this->getModulePath());
			$this->info("Modules folder has been set up.");
		}
		else
		{
			$this->comment("Modules folder is already set up");
		}
	}

	/**
	 * Setup modules assets folder.
	 *
	 * @return mixed
	 */
	protected function setupModuleAssetsFolder()
	{		
		if( ! $this->hasModuleAssetsPath())
		{
			$this->files->makeDirectory($this->getModuleAssetsPath());
			$this->info("Modules assets folder has been set up.");
		}
		else
		{
			$this->comment("Modules assets folder is already set up");
		}
	}

	/**
	 * Determine if the modules folder is already setup.
	 *
	 * @return boolean
	 */
	protected function hasModulePath()
	{
		return $this->files->exists($this->getModulePath());
	}

	/**
	 * Determine if the modules assets folder is already setup.
	 *
	 * @return boolean
	 */
	protected function hasModuleAssetsPath()
	{
		return $this->files->exists($this->getModuleAssetsPath());
	}

	/**
	 * Get modules path from configuration.
	 *
	 * @return string
	 */
	protected function getModulePath()
	{
		return $this->module->getPath();
	}

	/**
	 * Get modules assets path from configuration.
	 *
	 * @return string
	 */
	protected function getModuleAssetsPath()
	{
		return $this->module->getAssetsPath();
	}
}
