<?php namespace Pingpong\Modules\Commands;

use Pingpong\Modules\Module;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMakeCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate new module.';

	/**
	 * The folders will be created.
	 *
	 * @var array
	 */
	protected $folders = [
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
	];

	/**
	 * The files will be created.
	 *
	 * @var array
	 */
	protected $files = [
		'start/global.php',
		'{{Name}}ServiceProvider.php',
		'filters.php',
		'routes.php',
		'lang/en/{{name}}.php',
		'config/{{name}}.php',
	];

	/**
	 * The stubs will be used.
	 *
	 * @var array
	 */
	protected $stubs = [
		'global.stub',
		'provider.stub',
		'filters.stub',
		'routes.stub',
		'lang.stub',
		'config.stub',
	];

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Module $module, File $file)
	{
		$this->module 	= $module;
		$this->file 	= $file;
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->name = strtolower($this->argument('name'));
		$this->Name = ucwords($this->argument('name'));
		
		if( ! $this->module->has($this->Name))
		{
			return $this->generate();
		}
		return $this->comment("Module [$this->Name] is already exists.");
	}

	/**
	 * Get a new module path.
	 *
	 * @return void
	 */
	public function generate()
	{
		$this->generateFolders();
		$this->generateFiles();
		$this->call('module:seed-make', ['module' => $this->name, 'seeder' => $this->Name, '--master']);
		$this->call('module:controller', ['module' => $this->name, 'controller' => $this->Name . 'Controller']);
		$this->info("Module [$this->name] has been created successfully.");
	}

	/**
	 * Get module path. If $name not null, we will use that.
	 *
	 * @param  string  $name
	 * @return string
	 */
	protected function getModulePath($name = null)
	{
		$path = $this->module->getPath();
		if($name)
		{
			return $path . "/$name/";
		}
		return $path;
	}

	/**
	 * Get destination file.
	 *
	 * @param  string  $file
	 * @return string
	 */
	protected function getDestinationFile($file)
	{
		return $this->getModulePath($this->Name) . $this->formatContent($file);
	}

	/**
	 * Generate new folders.
	 *
	 * @return void
	 */
	protected function generateFolders()
	{
		$this->file->makeDirectory($this->getModulePath($this->Name));
		foreach ($this->folders as $folder) {
			$this->file->makeDirectory($this->getModulePath($this->Name) . $folder);
		}
	}

	/**
	 * Generate new files.
	 *
	 * @return void
	 */
	protected function generateFiles()
	{
		foreach ($this->files as $key => $file) {
			$this->makeFile($key, $file);
			$this->info("Created : " . $this->getDestinationFile($file));
		}
	}

	/**
	 * Create new file.
	 *
	 * @param 	int     $key
	 * @param 	string  $file
	 * @return 	string
	 */
	public function makeFile($key, $file)
	{
		return $this->file->put($this->getDestinationFile($file), $this->getStubContent($key));
	}

	/**
	 * Get stub content by given key.
	 *
	 * @param  int    $key
	 * @return string
	 */
	protected function getStubContent($key)
	{
		return $this->formatContent($this->file->get(__DIR__ . '/stubs/' . $this->stubs[$key]));
	}

	/**
	 * Replace the specified text from given content.
	 *
	 * @return string
	 */
	protected function formatContent($content)
	{
		return str_replace(['{{Name}}', '{{name}}'], [$this->Name, $this->name], $content);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'The name of module will be created.'),
		);
	}

}
