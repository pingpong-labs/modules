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
        'Assets/',
        'Console/',
        'Config/',
        'Http/Controllers/',
        'Http/Filters/',
        'Http/Requests/',
        'Database/Migrations/',
        'Database/Seeders/',
        'Database/Models/',
        'Database/Repositories/',
        'Providers/',
        'Resources/lang/en',
        'Resources/views/',
        'Tests/',
    );

    /**
     * The files will be created.
     *
     * @var array
     */
    protected $files = array(
        'start.php',
        'Providers/{{Name}}ServiceProvider.php',
        'Http/routes.php',
        'Resources/lang/en/{{name}}.php',
        'Config/{{name}}.php',
        'module.json'
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
        'json.stub'
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
     * @param Filesystem $finder
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
        $this->name = strtolower($name);
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
            $folderPath = $this->getModulePath($this->Name) . $folder;

            $this->finder->makeDirectory($folderPath, 0755, true);
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
        if ($name) return $this->module->getModulePath($name);

        return $this->module->getPath();
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