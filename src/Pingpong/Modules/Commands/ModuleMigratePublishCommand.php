<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Pingpong\Modules\Publishing\MigrationPublisher;

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        with(new MigrationPublisher($this->argument('module')))
            ->setModule($this->laravel['modules'])
            ->setFilesystem($this->laravel['files'])
            ->setConfig($this->laravel['config'])
            ->setConsole($this)
            ->publish();
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
