<?php namespace Pingpong\Modules;

use Countable;
use Illuminate\View\Factory;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;

class FileMissingException extends \Exception {}

class Module implements Countable
{
	/**
     * The Pingpong Themes Finder Object.
     *
     * @var Finder
     */
    protected $finder;

    /**
     * The Laravel Config Repository.
     *
     * @var Repository
     */
    protected $config;

    /**
     * The Laravel Translator.
     *
     * @var Translator
     */
    protected $lang;

    /**
     * The Laravel View.
     *
     * @var Factory
     */
    protected $views;

    /**
     * The constructor.
     *
     * @param Finder $finder
     * @param Repository $config
     * @param Factory $views
     * @param Translator $lang
     * @internal param Factory $view
     */
    public function __construct(
    	Finder $finder,
    	Repository $config,
    	Factory $views,
    	Translator $lang,
    	Filesystem $files
    )
    {
        $this->finder = $finder;
        $this->config = $config;
        $this->lang = $lang;
        $this->views = $views;
        $this->files = $files;
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
	 * Get count of all modules.
	 * 
	 * @return int 
	 */
	public function count()
	{
		return count($this->all());
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
		foreach ($this->all() as $module)
        {
			$this->includeGlobalFile($module);
		}
	}

	/**
	 * Get global.php file for the specified module.
	 *
	 * @param  	string   $name
	 * @return 	string
     * @throws  \Pingpong\Modules\FileMissingException
	 */
	protected function includeGlobalFile($name)
	{
		$file =  $this->getPath() . "/$name/start/global.php";
        if ( ! $this->files->exists($file))
        {
            throw new FileMissingException("Module [$name] must be have start/global.php file for registering namespaces.");
        }
        require $file;
	}

	/**
	 * Get modules path.
	 *
	 * @return 	string
	 */
	public function getPath()
	{
		return $this->config->get('modules::paths.modules');
	}

	/**
	 * Get module assets path.
	 *
	 * @return 	string
	 */
	public function getAssetsPath()
	{
		return $this->config->get('modules::paths.assets');
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

    /**
     * Set modules path in "RunTime" mode.
     *
     * @param $path
     */
    public function setPath($path)
    {
        $this->finder->setPath($path);
    }

    /**
     * Get module path for the specified module.
     *
     * @param $module
     * @return string
     */
    public function getModulePath($module)
    {
    	return $this->finder->getModulePath($module);
    }
}
