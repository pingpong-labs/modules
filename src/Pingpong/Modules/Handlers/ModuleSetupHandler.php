<?php namespace Pingpong\Modules\Handlers;

use Pingpong\Modules\Module;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ModuleSetupHandler
 * @package Pingpong\Modules\Handlers
 */
class ModuleSetupHandler {
    
    /**
     * The Module Instance.
     *
     * @var Module
     */
    protected $module;

    /**
     * The Laravel Filesystem Instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * The Laravel Command Instance.
     *
     * @var Command
     */
    protected $console;

    /**
     * The constructor.
     *
     * @param Module $module
     * @param Filesystem $files
     */
    public function __construct(Module $module, Filesystem $files)
    {
        $this->module = $module;
        $this->files = $files;
    }

    /**
     * Setup the modules.
     *
     * @param $console
     */
    public function fire(Command $console)
    {
        $this->console = $console;

        $this->setupModulesFolder();

        $this->setupAssetsFolder();
    }

    /**
     * Create the specified folder.
     *
     * @param $folder
     * @param $success
     * @param $error
     */
    protected function createFolder($folder, $success, $error)
    {
        if (!is_dir($folder))
        {
            $this->files->makeDirectory($folder);

            return $this->console->info($success);
        }

        return $this->console->comment($error);
    }

    /**
     * Setup the modules folder.
     */
    protected function setupModulesFolder()
    {
        $this->createFolder(
            $this->module->getPath(),
            'The modules folder has been created successful.',
            'The modules already exist.'
        );
    }

    /**
     * Setup the module assets folder.
     */
    protected function setupAssetsFolder()
    {
        $this->createFolder(
            $this->module->getAssetsPath(),
            'The module assets folder has been created successful.',
            'The module assets already exist.'
        );
    }
} 