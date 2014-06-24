<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMigratePublishCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:publish-migration';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Publish a module's migrations to the application";

	/**
	 * Create a new command instance.
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
        $module = $this->argument('module');

        if(empty($module))
        {
            foreach($this->laravel['modules']->all() as $module)
            {
                $this->publishMigration($module);
            }
        }
        else
        {
            $this->publishMigration($module);
        }
	}

    /**
     * Publish migration to the application for the specified module.
     *
     * @param $module
     */
    protected function publishMigration($module)
    {
        $path = $this->getMigrationPath($module);

        $this->laravel['files']->copyDirectory($path, app_path('database/migrations/'));

        $this->info("Published from : ". $path);
    }

    /**
     * Get migration path for the specified module.
     *
     * @param $module
     * @return string
     */
    protected function getMigrationPath($module)
    {
        return $this->laravel['modules']->getModulePath($module) .'/database/migrations/';
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('module', InputArgument::OPTIONAL, 'Module name.'),
		);
	}

}
