<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;

class ListCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show list of all modules.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        foreach ($this->laravel['modules']->all() as $module)
        {
            $this->line('- ' . $module);
        }
    }

}
