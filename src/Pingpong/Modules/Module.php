<?php namespace Pingpong\Modules;

use Illuminate\Foundation\Application;

class Module
{
	protected $app;

	public function __construct(Application $app)
	{
		$this->app = $app;
		$this->finder = $app['modules.finder'];
	}

	public function all()
	{
		return $this->finder->all();
	}

	public function has($name)
	{
		return $this->finder->exists(ucwords($name)) || $this->finder->exists(ucfirst($name));
	}

	public function register()
	{
		foreach ($this->all() as $module) {
			require $this->getGlobalFile($module);
		}
	}	
	
	protected function getGlobalFile($name)
	{
		return $this->getPath() . "/$name/start/global.php";
	}

	public function getPath()
	{
		return $this->app['config']->get('modules::paths.modules');
	}

	public function getAssetsPath()
	{
		return $this->app['config']->get('modules::paths.assets');
	}
}