<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Pingpong\Modules\Generators\ModuleGenerator;
use Symfony\Component\Console\Input\InputArgument;

class MakeCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new module.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $names = $this->argument('name');

        foreach ($names as $name)
        {
            with(new ModuleGenerator($name))
                ->setFilesystem($this->laravel['files'])
                ->setModule($this->laravel['modules'])
                ->setConfig($this->laravel['config'])
                ->setConsole($this)
                ->generate();
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
            array('name', InputArgument::IS_ARRAY, 'The names of modules will be created.'),
        );
    }

}
