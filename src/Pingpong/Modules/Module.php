<?php namespace Pingpong\Modules;

use Countable;
use Illuminate\View\Factory;
use Illuminate\Html\HtmlBuilder;
use Illuminate\Config\Repository;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Filesystem\Filesystem;
use Pingpong\Modules\Process\Updater;
use Illuminate\Translation\Translator;
use Pingpong\Modules\Process\Installer;
use Pingpong\Modules\Exceptions\FileMissingException;

class Module implements Countable {

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
     * @var HtmlBuilder
     */
    protected $html;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * The constructor.
     *
     * @param Finder $finder
     * @param Repository $config
     * @param Factory $views
     * @param Translator $lang
     */
    public function __construct(
        Finder $finder,
        Repository $config,
        Factory $views,
        Translator $lang,
        Filesystem $files,
        HtmlBuilder $html,
        UrlGenerator $url
    )
    {
        $this->finder = $finder;
        $this->config = $config;
        $this->lang = $lang;
        $this->views = $views;
        $this->files = $files;
        $this->html = $html;
        $this->url = $url;
    }

    /**
     * Get Modules Finder instance.
     *
     * @return Finder
     */
    public function getFinder()
    {
        return $this->finder;
    }

    /**
     * Get Laravel Config Instance.
     *
     * @return Repository
     */
    public function getConfigRepository()
    {
        return $this->config;
    }

    /**
     * Get Laravel View Factory Instance.
     *
     * @return Factory
     */
    public function getViewsFactory()
    {
        return $this->views;
    }

    /**
     * Get Laravel Translator Instance.
     *
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->lang;
    }

    /**
     * Get Laravel Filesystem Instance.
     *
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->files;
    }

    /**
     * Get Laravel Html Builder Instance.
     *
     * @return HtmlBuilder
     */
    public function getHtmlBuilder()
    {
        return $this->html;
    }

    /**
     * Get Laravel URL Generator Instance.
     *
     * @return UrlGenerator
     */
    public function getUrlGenerator()
    {
        return $this->url;
    }

    /**
     * Get all modules.
     *
     * @return    array
     */
    public function all()
    {
        return $this->finder->all();
    }

    /**
     * @param int $status
     * @return array
     */
    public function getByStatus($status = 1)
    {
        $data = array();

        foreach ($this->all() as $module)
        {
            if ($status == 1)
            {
                if ($this->active($module))
                {
                    $data[] = $module;
                }
            }
            else
            {
                if ($this->notActive($module))
                {
                    $data[] = $module;
                }
            }
        }

        return $data;
    }

    /**
     * @return array
     */
    public function enabled()
    {
        return $this->getByStatus(1);
    }

    /**
     * @return array
     */
    public function disabled()
    {
        return $this->getByStatus(0);
    }

    /**
     * Determine if the module exists.
     *
     * @param    string $name
     * @return    string
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
     * @param    string $name
     * @return    string
     */
    public function exists($name)
    {
        return in_array($name, $this->all());
    }

    /**
     * Register the global.php file from all modules.
     *
     * @return    string
     */
    public function register()
    {
        foreach ($this->enabled() as $module)
        {
            $this->includeGlobalFile($module);
        }
    }

    /**
     * Register start file.
     *
     * @param    string $name
     * @throws  \Pingpong\Modules\Exceptions\FileMissingException
     */
    protected function includeGlobalFile($name)
    {
        $file = $this->getModulePath($name) . "/start.php";

        if ( ! $this->files->exists($file))
        {
            $message = "Module [{$name}] must have start.php file for registering namespaces.";

            throw new FileMissingException($message);
        }

        require $file;
    }

    /**
     * Get modules path.
     *
     * @return    string
     */
    public function getPath()
    {
        return $this->config->get('modules::paths.modules');
    }

    /**
     * Get module assets path.
     *
     * @return    string
     */
    public function getAssetsPath()
    {
        return $this->config->get('modules::paths.assets');
    }

    /**
     * Generate a asset url for the specified module.
     *
     * @param    string $name
     * @param    string $url
     * @param    boolean $secure
     * @return    string
     */
    public function asset($name, $url, $secure = false)
    {
        return $this->url->asset(basename($this->getAssetsPath()) . "/{$name}/" . $url, $secure);
    }

    /**
     * Generate a link to a CSS file.
     *
     * @param  string $name
     * @param  string $url
     * @param  array $attributes
     * @return string
     */
    public function style($name, $url, $attributes = array(), $secure = false)
    {
        $defaults = array('media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet');

        $attributes = $attributes + $defaults;

        $attributes['href'] = $this->asset($name, $url, $secure);

        return '<link' . $this->html->attributes($attributes) . '>' . PHP_EOL;
    }

    /**
     * Generate a link to a JavaScript file.
     *
     * @param  string $name
     * @param  string $url
     * @param  array $attributes
     * @return string
     */
    public function script($name, $url, $attributes = array(), $secure = false)
    {
        $attributes['src'] = $this->asset($name, $url, $secure);

        return '<script' . $this->html->attributes($attributes) . '></script>' . PHP_EOL;
    }

    /**
     * Set modules path in "RunTime" mode.
     *
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->finder->setPath($path);

        return $this;
    }

    /**
     * Get module path for the specified module.
     *
     * @param $module
     * @return string
     */
    public function getModulePath($module)
    {
        return $this->finder->getModulePath($module, true);
    }

    /**
     * Get module json data by a given module name.
     *
     * @param $module
     * @return array|mixed
     */
    public function getProperties($module)
    {
        return $this->finder->getJsonContents($module);
    }

    /**
     * Get property.
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function property($key, $default = null)
    {
        return $this->finder->property($key, $default);
    }

    /**
     * Alias for "property" method.
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function prop($key, $default = null)
    {
        return $this->property($key, $default);
    }

    /**
     * Check if a given module active.
     *
     * @param $module
     * @return bool
     */
    public function active($module)
    {
        return $this->prop("{$module}::active") == 1;
    }

    /**
     * Check if a given module not active.
     *
     * @param $module
     * @return bool
     */
    public function notActive($module)
    {
        return ! $this->active($module);
    }

    /**
     * Enable the specified module.
     *
     * @param $module
     * @return mixed
     */
    public function enable($module)
    {
        return $this->finder->enable($module);
    }

    /**
     * Disable the specified module.
     *
     * @param $module
     * @return mixed
     */
    public function disable($module)
    {
        return $this->finder->disable($module);
    }

    /**
     * Get modules used now.
     *
     * @return string
     */
    public function getUsedNow()
    {
        return $this->finder->getUsed();
    }

    /**
     * Update dependencies for the specified module.
     *
     * @param  string $module
     * @return void
     */
    public function update($module)
    {
        with(new Updater($this))->update($module);
    }

    /**
     * Install the specified module.
     *
     * @param  string $name
     * @param  string $path
     * @param bool $subtree
     * @return void
     */
    public function install($name, $path = null, $subtree = false)
    {
        with(new Installer($this))->install($name, $path, $subtree);
    }

}
