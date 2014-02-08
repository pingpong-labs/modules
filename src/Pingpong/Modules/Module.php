<<<<<<< HEAD
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
=======
<?php

namespace Pingpong\Modules;

class Module
{
	protected $path;
	protected $ignores = array('.', '..');
	
	public static $instance = null;

	function __construct() {
		$this->path = base_path('modules').'/';
	}

	public static function getInstance()
	{
		if(is_null(static::$instance))
		{
			static::$instance = new self;
		}
		return static::$instance;
	}

	/**
	 * return all modules.
	 *
	 * @return void
	 */
	public function all()
	{
		$modules 	= array();
		$path 		= $this->path;
		
		if( is_dir($path)){		
			$folders = scandir($path);
			foreach ($folders as $folder) {
				if( ! in_array($folder, $this->ignores))
				{
					$modules[] = $folder;
				}	
			}
		}
		return $modules;
	}

	/**
	 * Register all modules.
	 *
	 * @return void
	 */
	public function register()
	{
		$path = $this->path;
		$modules = $this->all();
		if(count($modules) > 0 )
		{
			foreach ($modules as $module) {
				$this->create($module);			

				// including routes
				if(file_exists($route = $path.$module.'/routes.php'))
				{
					require $route;
				}

				// including filters
				if(file_exists($filter = $path.$module.'/filters.php'))
				{
					require $filter;
				}
			}
		}
	}	

	/**
	 * Adding new namespaces for all registered modules.
	 *
	 * @return void
	 */
	public function addNamespaces()
	{		
		$path = $this->path;
		foreach ($this->all() as $key => $name) {

			\Lang::addNamespace($name, $path.$name.'/lang');

		}
	}

	/**
	 * Creating new services, namespaces and others.
	 *
	 * @return void
	 */
	protected function create($module)
	{		
		$path = $this->path;

		\View::addNamespace($module, $path.$module.'/views');

		\Config::addNamespace($module, $path.$module.'/config');

		// create aliases for controllers and models
		$use_alias = \Config::get($module.'::app.alias');
		if($use_alias == TRUE)
		{
			$this->createAliases($module);		
		}

		$modulePath = $path.$module;
		\ClassLoader::addDirectories(array(

			$modulePath.'/commands',
			$modulePath.'/controllers',
			$modulePath.'/models',
			$modulePath.'/database/seeds',

		));
	}

	/**
	 * Creating new aliases.
	 *
	 * @return void
	 */
	protected function createAliases($module)
	{
		$path = $this->path;
		$controllers 	= $path.$module.'/Controllers';	
		$models 		= $path.$module.'/Models';

		$ControllersAliases = $this->getAliases($module, 'Controllers', $controllers);
		$ModelAliases 		= $this->getAliases($module, 'Models', $models);

		// create aliases for controller
		$facades = $ControllersAliases;
		$this->app->booting(function() use ($facades)
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			foreach ($facades as $key => $value) {
				$loader->alias($key, $value);
			}
		});

		// create aliases for models
		$facades = $ModelAliases;
		$this->app->booting(function() use ($facades)
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			foreach ($facades as $key => $value) {
				$loader->alias($key, $value);
			}
		});
	}

	/**
	 * Get all files.
	 *
	 * @return void
	 */
	protected function getAliases($module, $type, $path)
	{
		$ignores = $this->ignores;
		if( ! is_dir($path))
		{
			throw new \Exception("Module path [$path] does not exists!");
		}
		
		$files = array();

		$folders = scandir($path);
		foreach ($folders as $folder) {
			if( ! in_array($folder, $ignores))
			{
				list($name, $ext) = explode(".", $folder);
				$files[$name] = "Modules\\$module\\$type\\$name";
			}	
		}

		return $files;
	}

>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
}