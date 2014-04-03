<?php namespace Pingpong\Modules\Commands;

use Pingpong\Modules\Module;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleSeedMakeCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:seed:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate new seeder for the specified module.';

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
		$this->moduleName 	= ucwords($this->argument('module'));
		$this->name 		= strtolower($this->argument('seeder'));
		$this->Name 		= ucwords($this->argument('seeder'));

		if($this->module->has($this->moduleName))
		{
			return $this->makeSeeder();
		}
		return $this->error("Module [$this->moduleName] does not exists.");
	}

	/**
	 * Make new seeder class.
	 *
	 * @return mixed
	 */
	protected function makeSeeder()
	{
		$filename		 = '{{Name}}DatabaseSeeder.php';
		$destinationFile = $this->getDestinationFile($filename);
		if($this->file->exists($destinationFile))
		{
			return $this->comment("That file is already exists.");
		}
		$this->makeFile($filename);
		return $this->info('Created : ' . $destinationFile);
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
	 * @param 	int     $key
	 * @param 	string  $file
	 * @return 	string
	 */
	public function makeFile($file)
	{
		return $this->file->put($this->getDestinationFile($file), $this->getStubContent());
	}

	/**
	 * Get stub content by given key.
	 *
	 * @return string
	 */
	protected function getStubContent()
	{
		return $this->formatContent($this->file->get(__DIR__ . '/stubs/seeder.stub'));
	}

	/**
	 * Replace the specified text from given content.
	 *
	 * @return string
	 */
	protected function formatContent($content)
	{
		return str_replace(['{{Name}}', '{{name}}', '{{master}}'], [$this->Name, $this->name, $master], $content);
	}

	/**
	 * Get seeder path.
	 *
	 * @return string
	 */
	protected function getPath()
	{
		$path = $this->module->getPath();
		return $path . "/$this->moduleName/database/seeds/";
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('module', InputArgument::REQUIRED, 'The name of module will be used.'),
			array('seeder', InputArgument::REQUIRED, 'The name of seeder will be created.'),
		);
	}

}
