<?php namespace Pingpong\Modules\Handlers;

use Illuminate\Support\Str;
use Pingpong\Modules\Module;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ModuleSeedMakerHandler
 * @package Pingpong\Modules\Handlers
 */
class ModuleSeedMakerHandler {

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
        $this->files = $files;
        $this->module = $module;
    }

    /**
     * Fire.
     *
     * @param Command $console
     * @param $module
     * @param $name
     * @return mixed|void
     */
    public function fire(Command $console, $module, $name)
    {
        $this->console = $console;
        $this->moduleName = Str::studly($module);
        $this->name = $name;
        $this->Name = Str::studly($name);

        if($this->module->has($this->moduleName)) return $this->makeSeeder();

        $console->error("Module [{$this->moduleName}] does not exists.");
    }

    /**
     * Make new seeder class.
     *
     * @return mixed
     */
    protected function makeSeeder()
    {
        $filename		 = '{{Name}}TableSeeder.php';

        $destinationFile = $this->getDestinationFile($filename);

        if($this->files->exists($destinationFile))
        {
            return $this->console->comment("That file is already exists.");
        } 

        $this->makeFile($filename);

        return $this->console->info('Created : ' . $destinationFile);
    }

    /**
     * Get destination file.
     *
     * @param  string  $file
     * @return string
     */
    protected function getDestinationFile($file)
    {
        return $this->getPath() . $this->formatContent($file);
    }

    /**
     * Create new file.
     *
     * @param 	string  $file
     * @return 	string
     */
    public function makeFile($file)
    {
        return $this->files->put($this->getDestinationFile($file), $this->getStubContent());
    }

    /**
     * Get stub content by given key.
     *
     * @return string
     */
    protected function getStubContent()
    {
        return $this->formatContent($this->files->get(__DIR__ . '/../Commands/stubs/seeder.stub'));
    }

    /**
     * Replace the specified text from given content.
     *
     * @param string $content
     * @return string
     */
    protected function formatContent($content)
    {
        return str_replace(array('{{Name}}', '{{name}}'), array($this->Name, $this->name), $content);
    }

    /**
     * Get seeder path.
     *
     * @return string
     */
    protected function getPath()
    {
        return $this->module->getModulePath($this->moduleName) . "Database/Seeders/";
    }
} 