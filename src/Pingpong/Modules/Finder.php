<?php namespace Pingpong\Modules;

use Countable;
use Illuminate\Support\Str;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

/**
 * Class Finder
 * @package Pingpong\Modules
 */
class Finder implements Countable {

    /**
     * The Laravel Filesystem.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * The Laravel Config Repository.
     *
     * @var Repository
     */
    protected $config;

    /**
     * The current module path.
     *
     * @var string
     */
    protected $path;

    /**
     * The constructor.
     *
     * @param Filesystem $files
     * @param Repository $config
     */
    public function __construct(Filesystem $files, Repository $config)
    {
        $this->files = $files;
        $this->config = $config;
    }

    /**
     * Get all modules.
     *
     * @return array
     */
    public function all()
    {
        $modules = array();
        $path = $this->getPath();

        if ( ! is_dir($path))
        {
            return $modules;
        }

        $folders = $this->files->directories($path);

        foreach ($folders as $module)
        {
            if ( ! Str::startsWith($module, '.'))
            {
                $modules[] = basename($module);
            }
        }

        return $modules;
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
        return $this->path ?: $this->config->get('modules::paths.modules');
    }

    /**
     * Check whether the given module in all modules.
     *
     * @param $module
     * @return bool
     */
    public function has($module)
    {
        return in_array($module, $this->all());
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
    public function getModulePath($module, $allowNotExists = false)
    {
        $module = Str::studly($module);

        if ( ! $this->has($module) && $allowNotExists === false)
        {
            return null;
        }

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

        if ( ! $this->has($module))
        {
            return $default;
        }

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
     * @param $module
     * @return bool
     */
    public function enable($module)
    {
        return $this->setActive($module, 1);
    }

    /**
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
     * Get The Laravel Config Repository.
     *
     * @return Repository
     */
    public function getConfig()
    {
        return $this->config;
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

        if ( ! $this->files->exists($path))
        {
            return null;
        }

        return $this->files->get($path);
    }
}