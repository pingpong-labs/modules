<?php namespace Pingpong\Modules\Handlers;

use Illuminate\Support\Str;
use Pingpong\Modules\Module;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ModuleGeneratorHandler
 * @package Pingpong\Modules\Handlers
 */
class ModuleGeneratorHandler
{
    /**
     * The folders will be created.
     *
     * @var array
     */
    protected $folders = array(
        'assets/',
        'commands/',
        'config/',
        'controllers/',
        'database/',
        'database/migrations/',
        'database/seeds/',
        'lang/',
        'lang/en/',
        'models/',
        'start/',
        'tests/',
        'views/',
    );

    /**
     * The files will be created.
     *
     * @var array
     */
    protected $files = array(
        'start/global.php',
        '{{Name}}ServiceProvider.php',
        'filters.php',
        'routes.php',
        'lang/en/{{name}}.php',
        'config/{{name}}.php',
    );

    /**
     * The stubs will be used.
     *
     * @var array
     */
    protected $stubs = array(
        'global.stub',
        'provider.stub',
        'filters.stub',
        'routes.stub',
        'lang.stub',
        'config.stub',
    );

    /**
     * @var Module
     */
    protected $module;

    /**
     * @var Filesystem
     */
    protected $finder;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var
     */
    protected $Name;

    /**
     * @param Module $module
     */
    public function __construct(Module $module, Filesystem $finder)
    {
        $this->module = $module;
        $this->finder = $finder;
    }

    /**
     * @param Command $console
     * @param $name
     * @return bool
     */
    public function fire(Command $console, $name)
    {
        $this->console = $console;
        $this->name = $name;
        $this->Name = Str::studly($name);

        if($this->module->has($this->Name))
        {
            $console->comment("Module [{$this->Name}] already exists.");

            return false;
        }

        $this->generate($console);
    }

    /**
     * @param Command $console
     * @return bool
     */
    protected function generate(Command $console)
    {
        $this->generateFolders();

        $this->generateFiles();

        $console->call('module:seed-make', array('module' => $this->name, 'name' => $this->Name, '--master'));

        $console->call('module:controller', array('module' => $this->name, 'controller' => $this->Name . 'Controller'));

        $console->info("Module [{$this->Name}] has been created successfully.");

        return true;
    }

    /**
     * Generate new folders.
     *
     * @return void
     */
    protected function generateFolders()
    {
        $this->finder->makeDirectory($this->getModulePath($this->Name));

        foreach ($this->folders as $folder)
        {
            $this->finder->makeDirectory($this->getModulePath($this->Name) . $folder);
        }
    }


    /**
     * Generate new files.
     *
     * @return void
     */
    protected function generateFiles()
    {
        foreach ($this->files as $key => $file)
        {
            $this->makeFile($key, $file);

            $this->console->info("Created : " . $this->getDestinationFile($file));
        }
    }

    /**
     * Get module path. If $name not null, we will use that.
     *
     * @param  string $name
     * @return string
     */
    protected function getModulePath($name = null)
    {
        $path = $this->module->getPath();

        if ($name) return $path . "/{$name}/";

        return $path;
    }

    /**
     * Get destination file.
     *
     * @param  string $file
     * @return string
     */
    protected function getDestinationFile($file)
    {
        return $this->getModulePath($this->Name) . $this->formatContent($file);
    }

    /**
     * Create new file.
     *
     * @param    int $key
     * @param    string $file
     * @return    string
     */
    public function makeFile($key, $file)
    {
        return $this->finder->put($this->getDestinationFile($file), $this->getStubContent($key));
    }

    /**
     * Get stub content by given key.
     *
     * @param  int $key
     * @return string
     */
    protected function getStubContent($key)
    {
        return $this->formatContent($this->finder->get(__DIR__ . '/../Commands/stubs/' . $this->stubs[$key]));
    }

    /**
     * Replace the specified text from given content.
     *
     * @return string
     */
    protected function formatContent($content)
    {
        return str_replace(array('{{Name}}', '{{name}}'), array($this->Name, $this->name), $content);
    }
}