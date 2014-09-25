<?php namespace Pingpong\Modules\Handlers;

use Pingpong\Modules\Module;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ModuleMigrationPublisherHandler
 * @package Pingpong\Modules\Handlers
 */
class ModuleMigrationPublisherHandler {

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

        if ( ! empty($module))
        {
            $this->publishMigration($module);
        }
        else
        {
            foreach ($this->module->all() as $module)
            {
                $this->publishMigration($module);
            }
        }
    }

    /**
     * Publish migration to the application for the specified module.
     *
     * @param $module
     */
    protected function publishMigration($module)
    {
        if ($this->module->has($module))
        {
            $path = $this->getMigrationPath($module);

            $this->files->copyDirectory($path, app_path('database/migrations/'));

            $this->console->call('dump-autoload');

            return $this->console->info("Published from : " . $path);
        }

        return $this->console->error("Module [{$module}] does not exists!");
    }

    /**
     * Get migration path for the specified module.
     *
     * @param $module
     * @return string
     */
    protected function getMigrationPath($module)
    {
        return $this->module->getModulePath($module) . 'Database/Migrations/';
    }
} 