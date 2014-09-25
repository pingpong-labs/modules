<?php namespace Pingpong\Modules\Handlers;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Pingpong\Modules\Module;

/**
 * Class ModulePublisherHandler
 * @package Pingpong\Modules\Handlers
 */
class ModulePublisherHandler {

    /**
     * @var Module
     */
    protected $module;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @param Module $module
     * @param Filesystem $files
     */
    public function __construct(Module $module, Filesystem $files)
    {
        $this->module = $module;
        $this->files = $files;
    }

    /**
     * @param Command $console
     * @param $module
     */
    public function fire(Command $console, $module)
    {
        $moduleName = Str::studly($module);

        if ( ! empty($moduleName))
        {
            foreach ($this->module->all() as $module)
            {
                $this->publish($module);
            }

            return $console->info("All assets from all modules has been published successfully.");
        }

        if ($this->module->exists($moduleName))
        {
            $this->publish($moduleName);

            return $console->info("Assets from module [{$moduleName}] has been published successfully.");
        }

        return $console->info("Module [{$moduleName}] does not exists.");
    }

    /**
     * Get assets path for the specified module.
     *
     * @param  string $name
     * @return string
     */
    protected function getAssetsPath($name)
    {
        return realpath($this->module->getModulePath($name) . "/Assets/");
    }

    /**
     * Get destination assets path for the specified module.
     *
     * @param  string $name
     * @return string
     */
    public function getDestinationPath($name)
    {
        $name = strtolower($name);

        return realpath($this->module->getAssetsPath()) . "/{$name}/";
    }

    /**
     * Publish assets from the specified module.
     *
     * @param  string $name
     * @return void
     */
    protected function publish($name)
    {
        $folder = $this->getAssetsPath($name);

        $dest = $this->getDestinationPath($name);

        $this->files->copyDirectory($folder, $dest);
    }

} 