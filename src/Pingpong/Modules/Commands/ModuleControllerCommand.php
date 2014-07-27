<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleControllerCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:controller';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate new restful controller for the specified module.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->module = $this->laravel['modules'];
		$this->moduleName 		= ucwords($this->argument('module'));
		$this->controllerName	= studly_case($this->argument('controller'));
		
		if($this->module->has($this->moduleName))
		{
			return $this->call('controller:make', $this->getParameters());
		}
		return $this->error("Module [$this->moduleName] doest not exists.");
	}

	/**
	 * Get parameters.
	 *
	 * @return array
	 */
	protected function getParameters()
	{
		return [
			'name'		=>  $this->controllerName,
			'--path'	=>	$this->getControllerPath(),
			'--only'	=>	$this->option('only'),
			'--except'	=>	$this->option('except'),
		];
	}

	/**
	 * Get controller path.
	 *
	 * @return string
	 */
	protected function getControllerPath()
	{
        $path = ltrim(str_replace(base_path(), '', $this->module->getPath()), '/');

        return $path . "/$this->moduleName/controllers";
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
			array('controller', InputArgument::REQUIRED, 'The name of the controller class.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('only', null, InputOption::VALUE_OPTIONAL, 'The methods that should be included'),
			array('except', null, InputOption::VALUE_OPTIONAL, 'The methods that should be excluded'),
		);
	}

}
