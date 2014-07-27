<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ModuleMigrateCommand
 * @package Pingpong\Modules\Commands
 */
class ModuleMigrateCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:migrate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Migrate the migrations from the specified module or from all modules.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->module = $this->laravel['modules'];

		$name = ucwords($this->argument('module'));

        if($name) return $this->migrate($name);

		foreach ($this->module->all() as $name)
        {
			$this->migrate($name);
		}
	}

	/**
	 * Run the migration from the specified module.
	 *
	 * @param  string  $name
	 * @return mixed
	 */
	protected function migrate($name)
	{
		if($this->module->has($name))
		{
			$params = $this->getParameter($name);

			return $this->call('migrate', $params);
		}
		return $this->error("Module [$name] does not exists.");
	}

	/**
	 * Get console paramenter.
	 *
	 * @param  string  $name
	 * @return array
	 */
	protected function getParameter($name)
	{
		$params = array();

        $params['--path'] = $this->getMigrationPath($name);

		if($option = $this->option('database'))
		{
			$params['--database'] = $option;
		}
		if($option = $this->option('pretend'))
		{
			$params['--pretend'] = $option;
		}
		if($option = $this->option('seed'))
		{
			$params['--seed'] = $option;
		}
		return $params;
	}

	/**
	 * Get migrations path.
	 *
	 * @return string
	 */
	protected function getMigrationPath($name)
	{
		return basename($this->module->getPath()) . "/{$name}/database/migrations/";
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
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
			array('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'),
			array('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'),
			array('seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'),
		);
	}

}
