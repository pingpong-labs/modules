<?php namespace Pingpong\Modules\Handlers;

use Illuminate\Support\Str;
use Pingpong\Modules\Module;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ModuleModelHandler
 * @package Pingpong\Modules\Handlers
 */
class ModuleModelHandler {

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
     * @param $name
     * @return mixed
     */
    public function fire(Command $console, $module, $name)
    {
        $this->console = $console;
        $this->moduleName 	= Str::studly($module);
        $this->modelName 	= $name;

        if( ! $this->module->has($this->moduleName))
        {
            $console->error("Module [{$this->moduleName}] does not exists.");

            return false;
        }

        if($this->exists())
        {
            $message = "Model [$this->modelName] is already exists on '{$this->moduleName}' module.";

            return $console->error($message);
        }

        return $this->generate();
    }

    /**
     * Get stub content.
     *
     * @return string
     */
    protected function getStubContent()
    {
        $content = $this->files->get(__DIR__.'/../Commands/stubs/model.stub');

        $fillable = $this->console->option('fillable');

        if($fillable)
        {
            $fillableArray = explode(',', $fillable);

            $fillable = "'" . implode("', '", $fillableArray) . "'";
        }

        return str_replace(['{{name}}', '{{fillable}}'], [$this->modelName, $fillable], $content);
    }

    /**
     * Generate new model if not exists.
     *
     * @return mixed
     */
    protected function generate()
    {
        if($this->files->put($this->getNewModel(), $this->getStubContent()))
        {
            return $this->console->info("Model [{$this->modelName}] created successfully.");
        }

        return $this->console->error("Model [{$this->modelName}] already exists.");
    }

    /**
     * Get model paths.
     *
     * @return string
     */
    protected function getModelPath()
    {
        return $this->module->getModulePath($this->moduleName) . "/models/";
    }

    /**
     * Get real path model.
     *
     * @return string
     */
    protected function getRealPathModel()
    {
        return realpath($this->getModelPath());
    }

    /**
     * Get new model name with full path.
     *
     * @return string
     */
    protected function getNewModel()
    {
        return $this->getRealPathModel() . "/{$this->modelName}.php";
    }

    /**
     * Determine if the file is already exists.
     *
     * @return boolean
     */
    protected function exists()
    {
        return file_exists($this->getNewModel());
    }
} 