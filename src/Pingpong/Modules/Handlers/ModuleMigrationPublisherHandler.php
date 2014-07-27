<?php namespace Pingpong\Modules\Handlers;

use Illuminate\Filesystem\Filesystem;
use Pingpong\Modules\Module;
use Illuminate\Console\Command;

/**
 * Class ModuleMigrationPublisherHandler
 * @package Pingpong\Modules\Handlers
 */
class ModuleMigrationPublisherHandler
{
    /**
     * @var Module
     */
    protected $module;

    /**
     * @var Filesystem
     */
    private $files;

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
        $this->console = $console;

        if (empty($module))
        {
            foreach ($this->module->all() as $module)
            {
                $this->publishMigration($module);
            }
        }
        else
        {
            $this->publishMigration($module);
        }
    }

    /**
     * Publish migration to the application for the specified module.
     *
     * @param $module
     */
    protected function publishMigration($module)
    {
        $path = $this->getMigrationPath($module);
        
        $this->files->copyDirectory($path, app_path('database/migrations/'));

        $this->console->info("Published from : " . $path);
    }

    /**
     * Get migration path for the specified module.
     *
     * @param $module
     * @return string
     */
    protected function getMigrationPath($module)
    {
        return $this->module->getModulePath($module) . '/database/migrations/';
    }
} 