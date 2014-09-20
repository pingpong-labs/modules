<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleInstallCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install the specified module by github repo name (username/reponame).';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$name = $this->argument('name');

        $info = explode('/', $name);

        $vendorName = array_get($info, 0);

        $moduleName = array_get($info, 1);

        $repoUrl = "git@github.com:{$name}.git";

        $path = realpath($this->option('path') ?: $this->laravel['modules']->getPath());

        $gitPath = realpath($this->laravel['modules']->getModulePath($moduleName) . '/.git/');

        $command = "cd {$path} && git clone {$repoUrl} && rm -rf {$gitPath}";

        passthru($command);

        $this->info("Module [{$name}] installed successfully.");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'The name of module will be installed.'),
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
			array('path', null, InputOption::VALUE_OPTIONAL, 'The installation path.', null),
		);
	}

}
