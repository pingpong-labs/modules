<?php namespace Pingpong\Modules;

use Countable;
use Illuminate\Support\Str;
use Pingpong\Modules\Process\Updater;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Pingpong\Modules\Process\Installer;

class Repository implements Countable {

    /**
     * The Laravel Filesystem.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * The current module path.
     *
     * @var string
     */
    protected $path;

    /**
     * The constructor.
     *
     * @param string $path
     * @param Filesystem $files
     */
    public function __construct($path, Filesystem $files = null)
    {
        $this->path = $path;
        $this->files = $files ?: new Filesystem;
    }

    /**
     * Set module path.
     *
     * @param $path
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get module path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get count of modules.
     *
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * Get module path by given module name.
     *
     * @param  string $module
     * @param  boolean $allowNotExists
     * @return null|string
     */
    public function getModulePath($module, $allowNotExists = true)
    {
        $module = Str::studly($module);

        if ( ! $this->has($module) && $allowNotExists === false) return null;

        return $this->getPath() . "/{$module}/";
    }

    /**
     * Get module json content as an array.
     *
     * @param $module
     * @return array|mixed
     */
    public function getJsonContents($module)
    {
        $module = Str::studly($module);

        $default = array();

        if ( ! $this->has($module)) return $default;

        $path = $this->getJsonPath($module);

        if ($this->files->exists($path))
        {
            $contents = $this->files->get($path);

            return json_decode($contents, true);
        }

        return $default;
    }

    /**
     * Get the specified property for the specified module.
     *
     * @param $data
     * @param null $default
     * @return mixed
     */
    public function property($data, $default = null)
    {
        list($module, $key) = explode('::', $data);

        return array_get($this->getJsonContents($module), $key, $default);
    }

    /**
     * Set active state for the specified module by given status data.
     *
     * @param $module
     * @param $status
     * @return bool
     */
    public function setActive($module, $status)
    {
        $data = $this->getJsonContents($module);

        if (count($data))
        {
            unset($data['active']);

            $data['active'] = $status;

            $this->updateJsonContents($module, $data);

            return true;
        }

        return false;
    }

    /**
     * Enable the given module
     *
     * @param $module
     * @return bool
     */
    public function enable($module)
    {
        return $this->setActive($module, 1);
    }

    /**
     * Disable the given module
     *
     * @param $module
     * @return bool
     */
    public function disable($module)
    {
        return $this->setActive($module, 0);
    }

    /**
     * Get The Laravel Filesystem.
     *
     * @return Filesystem
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Update JSON content for the specified module by given array data.
     *
     * @param $module
     * @param array $data
     * @return int
     */
    public function updateJsonContents($module, array $data)
    {
        $contents = json_encode($data, JSON_PRETTY_PRINT);

        return $this->files->put($this->getJsonPath($module), $contents);
    }

    /**
     * Get JSON path for the specified module.
     *
     * @param $module
     * @return string
     */
    public function getJsonPath($module)
    {
        return $this->getModulePath($module) . '/module.json';
    }

    /**
     * Get module used storage path.
     *
     * @return string
     */
    public function getUsedPath()
    {
        return __DIR__ . '/../../modules.used';
    }

    /**
     * Set modules used.
     *
     * @param string $module
     */
    public function setUsed($module)
    {
        $this->files->put($this->getUsedPath(), $module);
    }

    /**
     * Get modules used for cli.
     *
     * @return mixed
     */
    public function getUsed()
    {
        $path = $this->getUsedPath();

        if ( ! $this->files->exists($path)) return null;

        return $this->files->get($path);
    }

    /**
     * Get all modules.
     *
     * @return array
     */
    public function all()
    {
        $modules = array();

        if ( ! is_dir($path = $this->getPath()))
        {
            return $modules;
        }

        $folders = $this->files->directories($path);

        foreach ($folders as $module)
        {
            if ( ! Str::startsWith($module, '.'))
            {
                $modules[] = new Module(basename($module), $this);
            }
        }

        return $modules;
    }

    /**
     * Get entity from a specified module by given module name.
     * 
     * @param  string $search
     * @return mixed
     */
    public function get($search)
    {
        foreach ($this->all() as $module)
        {
            if($module->getLowerName() == strtolower($search))
            {
                return $module;
            }
        }

        return null;
    }

    /**
     * Register modules providers.
     * 
     * @param  Application $app
     * @return void
     */
    public function registerModulesProviders(Application $app)
    {
        foreach ($this->all() as $module)
        {
            $providers = $module->present()->getProviders();
            
            foreach ($providers as $provider)
            {
                $app->register($provider);
            }
        }
    }

    /**
     * Get all enabled (status = 1) or disabled (status = 0) modules
     *
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
     * Return all enabled modules
     *
     * @return array
     */
    public function enabled()
    {
        return $this->getByStatus(1);
    }

    /**
     * Return all disabled modules
     *
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
     * Get modules used now.
     *
     * @return string
     */
    public function getUsedNow()
    {
        return $this->getUsed();
    }

    /**
     * Register all enabled modules.
     * 
     * @return void
     */
    public function register()
    {
        foreach ($this->enabled() as $module)
        {
            $module->register();
        }
    }

    /**
     * Get asset url from specified module path.
     * 
     * @param  string  $path
     * @param  boolean $secure
     * @return string
     */
    public function asset($path, $secure = false)
    {
        list($module, $url) = explode(':', $path);

        return app('url')->asset("modules/{$module}/{$url}");
    }

    /**
     * Get style tag from the given url.
     * 
     * @param  string  $url
     * @param  boolean $secure
     * @return string
     */
    public function style($url, $secure = false)
    {
        return app('html')->style($this->asset($url, $secure));
    }

    /**
     * Get script tag from the given url.
     * 
     * @param  string  $url
     * @param  boolean $secure
     * @return string
     */
    public function script($url, $secure = false)
    {
        return app('html')->script($this->asset($url, $secure));
    }

    /**
     * Get asset path from the specfied module.
     * 
     * @param  string  $path
     * @param  boolean $secure
     * @return string
     */
    public function assetPath($path = null, $secure = false)
    {
        $assetsPath = app('config')->get('modules::paths.assets');

        if(is_null($path)) return $assetsPath;

        if( str_contains($path, ':'))
        {
            list($module, $url) = explode(':', $path);

            return $assetsPath . "/{$module}/{$url}";
        }

        return $assetsPath . '/' . $path;
    }

    /**
     * Get a specific modules configuration.
     * 
     * @param  string $key
     * @param  null|mixed $default
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return app('config')->get("modules::paths.{$key}", $default);
    }

}