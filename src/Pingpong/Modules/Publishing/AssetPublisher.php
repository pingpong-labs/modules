<?php namespace Pingpong\Modules\Publishing;

use Illuminate\Support\Str;
use Pingpong\Modules\Module;
use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

class AssetPublisher extends Publisher {

    /**
     * The pingpong module instance.
     *
     * @var Module
     */
    protected $module;

    /**
     * The name of module will published.
     *
     * @var string
     */
    protected $name;

    /**
     * The laravel filesystem instance.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * The laravel config instance.
     *
     * @var Repository
     */
    protected $config;

    /**
     * The laravel console instance.
     *
     * @var Command
     */
    protected $console;

    protected $success = '';

    protected $error = '';

    /**
     * The constructor.
     *
     * @param null $name
     * @param Module $module
     * @param Filesystem $filesystem
     * @param Repository $config
     * @param Command $console
     */
    public function __construct($name = null, Module $module = null, Filesystem $filesystem = null, Repository $config = null, Command $console = null)
    {
        $this->module = $module;
        $this->name = $name;
        $this->filesystem = $filesystem;
        $this->config = $config;
        $this->console = $console;
    }

    /**
     * Get the name of module will published.
     *
     * @return string
     */
    public function getName()
    {
        return Str::studly($this->name);
    }

    /**
     * Get the laravel config instance.
     *
     * @return Repository
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the laravel config instance.
     *
     * @param Repository $config
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get the laravel filesystem instance.
     *
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Set the laravel filesystem instance.
     *
     * @param Filesystem $filesystem
     * @return $this
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * Get the pingpong module instance.
     *
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set the pingpong module instance.
     *
     * @param Module $module
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get the laravel console instance.
     *
     * @return Command
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Set the laravel console instance.
     *
     * @param Command $console
     * @return $this
     */
    public function setConsole($console)
    {
        $this->console = $console;

        return $this;
    }

    /**
     * Publish the assets from the specified module.
     *
     * @return mixed
     */
    public function publish()
    {
        if (is_null($module = $this->getName()))
        {
            $this->publishFromAllModules();

            return;
        }

        $this->publishFromModule($module);
    }

    /**
     * Publish assets from all modules.
     */
    protected function publishFromAllModules()
    {
        foreach ($this->module->all() as $module)
        {
            $this->publishFromModule($module);
        }
    }

    /**
     * Publish assets form the specified module.
     *
     * @param $module
     */
    protected function publishFromModule($module)
    {
        if ( ! $this->module->has($module))
        {
            $this->console->error("Module [{$module}] does not exist.");

            exit;
        }

        $this->filesystem->copyDirectory($this->getPublishingPath($module), $this->getDestinationPath($module));

        $this->console->info("Assets published from module : {$module}");
    }

    /**
     * Get asset path from the specified module.
     *
     * @param $module
     * @return string
     */
    protected function getPublishingPath($module)
    {
        return $this->module->getModulePath($module) . $this->config->get('modules::paths.generator.assets');
    }

    /**
     * Get the destination path for the specified module.
     *
     * @param $module
     * @return string
     */
    protected function getDestinationPath($module)
    {
        return $this->module->getAssetsPath() . '/' . strtolower($module);
    }

}