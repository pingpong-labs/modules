<?php namespace Pingpong\Modules;

use Illuminate\Foundation\Application;

class Collection
{
	/**
	 * Application object
	 * 
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Filesystem object
	 * 
	 * @var Illuminate\Filesystem\Filesystem
	 */
	protected $file;

	/**
	 * String path module
	 * 
	 * @var string
	 */
	protected $path;

	/**
	 * String asset path for each module
	 * 
	 * @var string
	 */
	protected $assetPath;

	/**
	 * Ignores up and base directories
	 * 
	 * @var array
	 */
	protected $ignores = array('.', '..');
	
	/**
	 * Self object
	 * 
	 * @var self
	 */
	public static $instance = null;

	public function __construct(Application $app) {
		$this->app = $app;
		$this->file = $this->app['files'];
		$this->path = $this->app['config']->get('modules.paths.modules', base_path() . '/modules/');
		$this->assetPath = $this->app['config']->get('modules.paths.assets', public_path() . '/modules/');
	}
	
	/**
	 * Self object
	 * 
	 * @return self
	 */
	public static function getInstance($app)
	{
		if(is_null(static::$instance))
		{
			static::$instance = new self($app);
		}
		return static::$instance;
	}

	/**
	 * return all modules.
	 *
	 * @return array
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
		$modules = $this->all();
		if(count($modules) > 0 )
		{
			foreach ($modules as $module) {
				$this->registerModule($module);
			}
		}
	}	

	/**
	 * Get module path.
	 *
	 * @return string
	 */
	public function getPath($module = null)
	{
		return is_null($module) ? $this->path : $this->path . $module . '/';
	}

	/**
	 * Get asset path.
	 *
	 * @return string
	 */
	public function getAssetPath($module = null)
	{
		return is_null($module) ? $this->assetPath : $this->assetPath . $module . '/';
	}

	/**
	 * Registering specified module.
	 *
	 * @param 	String 	$module 
	 * @return void
	 */
	protected function registerModule($module)
	{
		$this->create($module);			

		if($this->file->exists($route = $this->path.$module.'/routes.php'))
		{
			require $route;
		}

		if($this->file->exists($filter = $this->path.$module.'/filters.php'))
		{
			require $filter;
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

			$this->app['translator']->addNamespace($name, $path.$name.'/lang');

		}
	}

	/**
	 * Creating new services, namespaces and others.
	 *
	 * @param 	String 	$module 
	 * @return void
	 */
	protected function create($module)
	{		
		$path = $this->path;

		$this->app['view']->addNamespace($module, $path.$module.'/views');

		$this->app['config']->addNamespace($module, $path.$module.'/config');

		// create aliases for controllers and models
		$use_alias = $this->app['config']->get($module.'::app.alias');
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
	 * @param 	String 	$module 
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
	 * Get alias from specified module.
	 *
	 * @param 	String 	$module 
	 * @param 	String 	$type 
	 * @param 	String 	$path 
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

}