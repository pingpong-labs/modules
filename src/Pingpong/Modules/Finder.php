<?php namespace Pingpong\Modules;

use Countable;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * Class Finder
 * @package Pingpong\Modules
 */
class Finder implements Countable
{
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
        $path    = $this->getPath();
        
        if( ! is_dir($path)) return $modules;

        $folders = $this->files->directories($path);
        
        foreach($folders as $module)
        {
            if( ! Str::startsWith($module, '.')) $modules[] = basename($module);
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
     * @param $module
     * @return null|string
     */
    public function getModulePath($module)
    {
        if( ! $this->has($module)) return null;

        return $this->getPath() . "/{$module}";
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
}