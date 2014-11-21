<?php namespace Pingpong\Modules;

use Countable;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;
use Pingpong\Modules\Contracts\RepositoryInterface;
use Pingpong\Modules\Exceptions\ModuleNotFoundException;

class Repository implements RepositoryInterface, Countable {

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var null
     */
    protected $path;

    /**
     * @param Application $app
     * @param string|null $path
     */
    public function __construct(Application $app, $path = null)
    {
        $this->app = $app;
        $this->path = $path;
    }

    /**
     * Get all modules.
     *
     * @return array
     */
    public function all()
    {
        $modules = [];

        $directories = $this->app['files']->directories($this->getPath());

        foreach ($directories as $module)
        {
            if ( ! Str::startsWith($name = basename($module), '.'))
            {
                $modules[$name] = new Module($this->app, $name, $module);
            }
        }

        return $modules;
    }

    /**
     * @param $status
     * @return array
     */
    public function getByStatus($status)
    {
        $modules = [];

        foreach ($this->all() as $name => $module)
        {
            if ($module->isStatus($status))
            {
                $modules[$name] = $modules;
            }
        }

        return $modules;
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->all());
    }

    /**
     * Get list of enabled modules.
     *
     * @return mixed
     */
    public function enabled()
    {
        return $this->getByStatus(1);
    }

    /**
     * Get list of disabled modules.
     *
     * @return mixed
     */
    public function disabled()
    {
        return $this->getByStatus(0);
    }

    /**
     * Get count from all modules.
     *
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * Get all ordered modules.
     *
     * @return mixed
     */
    public function getOrdered()
    {
        $modules = $this->all();

        uasort($modules, function ($a, $b)
        {
            if ($a->priority == $b->priority)
            {
                return 0;
            }

            return $a->priority < $b->priority ? -1 : 1;
        });

        return $modules;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path ?: $this->app['config']->get('modules::paths.modules');
    }

    /**
     *
     */
    public function register()
    {
        foreach ($this->getOrdered() as $module)
        {
//            var_dump($module);

//            $module->register();
        }
    }

    /**
     * @param $name
     * @return null
     */
    public function find($name)
    {
        foreach ($this->all() as $module)
        {
            if ($module->getLowerName() == strtolower($name))
            {
                return $module;
            }
        }

        return null;
    }

    /**
     * @param $name
     * @return null
     */
    public function get($name)
    {
        return $this->find($name);
    }

    /**
     * @param $name
     * @throws ModuleNotFoundException
     */
    public function findOrFail($name)
    {
        if ( ! is_null($module = $this->find($name)))
        {
            return $module;
        }

        throw new ModuleNotFoundException("Module [{$name}] does not exist!");
    }

    /**
     * @return Collection
     */
    public function collections()
    {
        return new Collection($this->enabled());
    }

    /**
     * @param $module
     * @return string
     */
    public function getModulePath($module)
    {
        $module = Str::studly($module);

        return $this->getPath() . "/{$module}/";
    }

    /**
     * @param $module
     * @return string
     */
    public function assetPath($module)
    {
        return $this->config('assets') . '/' . $module;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function config($key)
    {
        return $this->app['config']->get('modules::paths.' . $key);
    }

    /**
     * @return string
     */
    public function getUsedStoragePath()
    {
        return storage_path('meta/modules.used');
    }

    /**
     * @param $name
     * @throws ModuleNotFoundException
     */
    public function setUsed($name)
    {
        $module = $this->findOrFail($name);

        $this->app['files']->put($this->getUsedStoragePath(), $module);
    }

    /**
     * @return string
     */
    public function getUsedNow()
    {
        return $this->app['files']->get($this->getUsedStoragePath());
    }

    /**
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFiles()
    {
        return $this->app['files'];
    }

}