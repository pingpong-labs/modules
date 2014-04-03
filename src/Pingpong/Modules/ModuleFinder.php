<?php namespace Pingpong\Modules;

use Illuminate\Foundation\Application;
use Illuminate\Support\Str;

class ModuleFinder
{
	protected $app;

	public function __construct(Application $app)
	{
		$this->app = $app;
		$this->files = $app['files'];
	}

	public function all()
	{
		$modules = array();
		foreach ($this->getDirectories() as $module) {
			$name = basename($module);
			if( ! Str::startsWith($name, '.'))
			{
				$modules[] = $name; 
			}
		}
		return $modules;
	}

	public function exists($name)
	{
		return in_array($name, $this->all());
	}

	protected function getDirectories()
	{
		return $this->files->directories($this->getModulesPath());
	}

	protected function getModulesPath()
	{
		return $this->app['modules']->getPath();
	}
}