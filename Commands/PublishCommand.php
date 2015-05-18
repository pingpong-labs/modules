<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Pingpong\Modules\Publishing\AssetPublisher;
use Symfony\Component\Console\Input\InputArgument;

class PublishCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish assets from the specified module or from all modules.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if ($name = $this->argument('module')) {
            return $this->publish($name);
        }

        $this->publishAll();
    }

    /**
     * Publish assets from all modules.
     *
     * @return void
     */
    public function publishAll()
    {
        foreach ($this->laravel['modules']->enabled() as $module) {
            $name = $module->getStudlyName();

            $this->publish($name);
        }
    }

    /**
     * Publish assets from the specified module.
     *
     * @param  string $name
     * @return void
     */
    public function publish($name)
    {
        $module = $this->laravel['modules']->findOrFail($name);

        with(new AssetPublisher($module))
            ->setRepository($this->laravel['modules'])
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
            array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
        );
    }
}
