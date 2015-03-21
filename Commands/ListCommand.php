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
        $this->table(['Name', 'Status', 'Priority', 'Path'], $this->getRows());
    }

    /**
     * Get table rows.
     * 
     * @return array
     */
    public function getRows()
    {
        $rows = [];

        foreach ($this->laravel['modules']->getOrdered() as $module)
        {
            $rows[] = [
                $module->getStudlyName(),
                $module->enabled() ? 'Enabled' : 'Disabled',
                $module->get('priority'),
                $module->getPath(),
            ];
        }

        return $rows;   
    }

}
