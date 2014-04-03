<?php namespace Pingpong\Modules\Commands;

use Pingpong\Modules\Module;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleModelCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:model';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate new model for the specified module.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Module $module, File $files)
	{
		$this->module = $module;
		$this->files  = $files;
 		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->moduleName 	= strtolower($this->argument('module'));
		$this->modelName 	= ucwords($this->argument('model'));
		if($this->exists())
		{
			return $this->error("Model [$this->modelName] is already exists on '$this->moduleName' module.");
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
		$content = $this->files->get(__DIR__.'/stubs/model.stub');
		return str_replace('{{name}}', $this->modelName, $content);
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
			return $this->info("Model [$this->modelName] created successfully.");
		}
		return $this->error("Can not create [$this->modelName] created successfully.");
	}

	/**
	 * Get model paths.
	 *
	 * @return string
	 */
	protected function getModelPath()
	{
		return basename($this->module->getPath()) . "/$this->moduleName/models/";
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
		return $this->getRealPathModel() . "/$this->modelName.php";
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

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('module', InputArgument::REQUIRED, 'The name of module will be used.'),
			array('model', InputArgument::REQUIRED, 'The name of model will be created.'),
		);
	}

}
