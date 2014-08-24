<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Pingpong\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleCommandCommand extends Command {

	use ModuleCommandTrait;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:command';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate new Artisan command for the specified module.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
    {
        $this->module = $this->laravel['modules'];

		$this->moduleName = $this->getModuleName();
        
        if($this->module->has($this->moduleName))
		{
			$params = [
				'name'	 		=>  $this->argument('name'),
				'--path' 		=>  $this->getPath(),
				'--namespace'	=>	$this->option('namespace'),
				'--command'		=>	$this->option('command'),
			];
        
            return $this->call('command:make', $params);
        
        }
        
        return $this->error("Module [{$this->moduleName}] does not exists.");
	}

	/**
	 * Get commands path.
	 *
	 * @return mixed
	 */
	protected function getPath()
	{
		return str_replace(base_path(), '', $this->module->getModulePath($this->moduleName)) . "commands";
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'The name of the command.'),
			array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
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
			array('command', null, InputOption::VALUE_OPTIONAL, 'The terminal command that should be assigned.', null),
			array('namespace', null, InputOption::VALUE_OPTIONAL, 'The command namespace.', null),
		);
	}

}
