<?php namespace Pingpong\Modules;

use Illuminate\Foundation\Application;
use Illuminate\Support\Str;

class ModuleFinder
{
	/**
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Constructor.
	 *
	 * @param \Illuminate\Foundation\Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
		$this->files = $app['files'];
	}

	/**
	 * Get all modules.
	 *
	 * @return 	array
	 */
	public function all()
	{
		$modules = array();
		if($this->getDirectories())
		{
			foreach ($this->getDirectories() as $module) {
				$name = basename($module);
				if( ! Str::startsWith($name, '.'))
				{
					$modules[] = $name; 
				}
			}
		}
		return $modules;
	}

	/**
	 * Get all directories from modules path.
	 *
	 * @return 	string
	 */
	protected function getDirectories()
	{
		if(is_dir($dir = $this->getModulesPath()))
		{
			return $this->files->directories($dir);
		}
		return null;
	}

	/**
	 * Get modules path.
	 *
	 * @return 	string
	 */
	protected function getModulesPath()
	{
		return $this->app['modules']->getPath();
	}
}