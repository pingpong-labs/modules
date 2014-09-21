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
        list($vendorName, $moduleName) = explode('/', $this->argument('name'));

        $this->installModule($vendorName, $moduleName);

        $this->installPackage($moduleName);

        $this->info("Module [{$name}] installed successfully.");
	}

	/**
	 * Install the specified module.
	 * 
	 * @param  string $moduleName 
	 * @return void
	 */
	public function installModule($moduleName)
	{
		$name = $this->argument('name');

		$repoUrl = "git@github.com:{$name}.git";

        $path = realpath($this->option('path') ?: $this->laravel['modules']->getPath());

        $gitPath = realpath($this->laravel['modules']->getModulePath($moduleName) . '/.git/');

        $command = "cd {$path} && git clone {$repoUrl} && rm -rf {$gitPath}";

        passthru($command);
	}

	/**
	 * Install the required package for the specified module.
	 * 
	 * @param  string $module 
	 * @return void         
	 */
	public function installPackage($module)
	{
		$packages = $this->laravel['modules']->prop($module . '::require', []);

        foreach ($packages as $name => $version)
        {
        	$package = "\"{$name}:{$version}\"";

        	passthru("composer require {$package}");
        }
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
