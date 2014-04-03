<?php namespace Pingpong\Modules;

use Illuminate\Foundation\Application;

class Module
{
	/**
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * @var \Pingpong\Modules\ModuleFinder
	 */
	protected $finder;

	/**
	 * Constructor.
	 *
	 * @param \Illuminate\Foundation\Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
		$this->finder = $app['modules.finder'];
	}

	/**
	 * Get all modules.
	 *
	 * @return 	array
	 */
	public function all()
	{
		return $this->finder->all();
	}

	/**
	 * Determine if the module exists.
	 *
	 * @param  	string   $name
	 * @return 	string
	 */
	public function has($name)
	{
		return in_array($name, $this->all());
	}

	/**
	 * Determine if the module exists.
	 *
	 * @param  	string   $name
	 * @return 	string
	 */
	public function exists($name)
	{
		return in_array($name, $this->all());
	}

	/**
	 * Register the global.php file from all modules.
	 *
	 * @return 	string
	 */
	public function register()
	{
		foreach ($this->all() as $module) {
			require $this->getGlobalFile($module);
		}
	}	
	
	/**
	 * Get global.php file for the specified module.
	 *
	 * @param  	string   $name
	 * @return 	string
	 */
	protected function getGlobalFile($name)
	{
		return $this->getPath() . "/$name/start/global.php";
	}

	/**
	 * Get modules path.
	 *
	 * @param  	string   $name
	 * @return 	string
	 */
	public function getPath()
	{
		return $this->app['config']->get('modules::paths.modules');
	}

	/**
	 * Get module assets path.
	 *
	 * @return 	string
	 */
	public function getAssetsPath()
	{
		return $this->app['config']->get('modules::paths.assets');
	}

	/**
	 * Generate a asset url for the specified module.
	 *
	 * @param  	string   $name
	 * @param 	string   $url
	 * @param 	boolean  $secure
	 * @return 	string
	 */
	public function asset($name, $url, $secure = false)
	{
		return $this->app['url']->asset( basename($this->getAssetsPath()) . "/$name/" . $url, $secure);
	}

	/**
	 * Generate a link to a CSS file.
	 *
	 * @param  string  $name
	 * @param  string  $url
	 * @param  array   $attributes
	 * @return string
	 */
	public function style($name, $url, $attributes = array(), $secure = false)
	{
		$defaults = array('media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet');

		$attributes = $attributes + $defaults;

		$attributes['href'] = $this->asset($name, $url, $secure);

		return '<link'.$this->app['html']->attributes($attributes).'>'.PHP_EOL;
	}

	/**
	 * Generate a link to a JavaScript file.
	 *
	 * @param  string  $name
	 * @param  string  $url
	 * @param  array   $attributes
	 * @return string
	 */
	public function script($name, $url, $attributes = array(), $secure = false)
	{
		$attributes['src'] = $this->asset($name, $url, $secure);

		return '<script'.$this->app['html']->attributes($attributes).'></script>'.PHP_EOL;
	}

}