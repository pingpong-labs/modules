<?php namespace Pingpong\Modules;

use Illuminate\Foundation\Application;

class Module
{
	/**
	 * Application object
	 * 
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Module collection object
	 * 
	 * @var Pingpong\Modules\Collection
	 */
	protected $collection;

	public function __construct(Application $app)
	{
		$this->app = $app;
		$this->collection = $this->app['module.collection'];
		$this->manifest = $this->app['module.manifest'];
	}

	/**
	 * Get all modules
	 *
	 * @return array
	 */
	public function all()
	{
		return $this->collection->all();
	}

	/**
	 * Get all modules with details
	 *
	 * @return array
	 */
	public function allWithDetails($option = TRUE)
	{
		$modules = array();
		foreach ($this->all() as $module) {
			$modules[$module] = $this->getDetails($module, $option);
		}
		return $modules;
	}
	
	/**
	 * Is module exists ?
	 *
	 * @return mixed
	 **/
	public function has($module)
	{
		$module = $this->getPath().$module.'/';
		return $this->app['files']->exists($module);
	}

	/**
	 * Get module details
	 *
	 * @return String
	 **/
	public function getDetails($module, $option = FALSE)
	{
		return $this->manifest->getDetails($module, $option);
	}

	/**
	 * Has json file ?
	 * 
	 * @param $module String
	 * @return boolean 
	 */
	public function hasJsonFile($module)
	{
		return $this->manifest->hasJsonFile($module);
	}

	/**
	 * Get JSON file from specified module
	 * 
	 * @param $module String
	 * @return object 
	 */
	public function getJsonFile($module)
	{
		return $this->manifest->getJsonFile($module);
	}

	/**
	 * Get JSON content from specified module
	 * 
	 * @param $module String
	 * @return object 
	 */
	public function getJsonContent($module)
	{
		return $this->manifest->getJsonContent($module);
	}

	/**
	 * Convert JSON module detail to object
	 * 
	 * @param $module String
	 * @param $option Boolean
	 * @return object 
	 */
	public function parseJson($module, $option)
	{
		return $this->manifest->parseJson($module, $option);
	}

	/**
	 * Get module path
	 *
	 * @return String
	 **/
	public function getPath()
	{
		return $this->collection->getPath();
	}

	/**
	 * Get module directory name
	 *
	 * @return String
	 **/
	public function getDirName()
	{
		return basename($this->getPath());
	}
	
	/**
	 * Generate a link to a CSS file from current theme.
	 *
	 * @param  string  $url
	 * @param  string  $module
	 * @param  array   $attributes
	 * @param  boolean $secure
	 * @return string
	 */
	public function style($module, $url, $attributes = array(), $secure = FALSE)
	{
		$defaults = array('media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet');

		$attributes = $attributes + $defaults;

		$attributes['href'] = $this->asset($module, $url, $secure);

		return '<link'.$this->app['html']->attributes($attributes).'>'.PHP_EOL;
	}

	/**
	 * Generate a link to a JavaScript file from current theme.
	 *
	 * @param  string  $url
	 * @param  string  $module
	 * @param  array   $attributes
	 * @return string
	 */
	public function script($module, $url, $attributes = array(), $secure = FALSE)
	{
		$attributes['src'] = $this->asset($module, $url, $secure);

		return '<script'.$this->app['html']->attributes($attributes).'></script>'.PHP_EOL;
	}

	/**
	 * Generate asset URL from current theme.
	 *
	 * @param  string  $url
	 * @param  string  $module
	 * @param  boolean $secure
	 * @return string
	 */
	public function asset($module, $url, $secure = FALSE)
	{
		$url = $this->getDirName().'/'.$module.'/assets/'.$url;
		return $this->app['url']->asset($url, $secure);
	}
}