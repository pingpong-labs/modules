<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class InstallCommand extends Command {

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
    protected $description = 'Install the specified module by given package name (vendor/name).';

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

        $this->info("Installing [{$name}] module");

        $this->laravel['modules']->install(
            $this->getPackageName(),
            $this->option('path'),
            $this->option('tree')
        );

        $this->info("Module [{$name}] installed successfully.");
    }

    /**
     * Get package name.
     *
     * @return string
     */
    protected function getPackageName()
    {
        $name = $this->argument('name');

        if ($version = $this->argument('version'))
        {
            $name = $name . ':' . $version;
        }

        return $name;
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
            array('version', InputArgument::OPTIONAL, 'The version of module will be installed.'),
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
            array('tree', null, InputOption::VALUE_NONE, 'Install the module as a git subtree', null),
        );
    }

}
