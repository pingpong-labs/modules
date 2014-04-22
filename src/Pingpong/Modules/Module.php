<?php namespace Pingpong\Modules;

use Illuminate\Foundation\Application;

class FileMissingException extends \Exception {}

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
	public function __construct(Application $app, ModuleFinder $finder)
	{
		$this->app = $app;
		$this->finder = $finder;
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
		return $this->exists($name);
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
			$this->includeGlobalFile($module);
		}
	}

	/**
	 * Get global.php file for the specified module.
	 *
	 * @param  	string   $name
	 * @return 	string
	 */
	protected function includeGlobalFile($name)
	{
		$file =  $this->getPath() . "/$name/start/global.php";
        if ( ! $this->app['files']->exists($file)
        {
            throw new FileMissingException("Module [$name] must be have start/global.php file for registering namespaces.");
        }
        require $file;
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
