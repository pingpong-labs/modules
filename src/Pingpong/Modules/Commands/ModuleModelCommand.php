<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Pingpong\Modules\Handlers\ModuleModelHandler;
use Symfony\Component\Console\Input\InputArgument;

class ModuleModelCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:model';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate new model for the specified module.';

    /**
     * @property \Pingpong\Modules\Handlers\ModuleModelHandler handler
     */
    protected $handler;

    /**
     * Create a new command instance.
     *
     * @param ModuleModelHandler $handler
     * @return \Pingpong\Modules\Commands\ModuleModelCommand
     */
	public function __construct(ModuleModelHandler $handler)
	{
 		parent::__construct();

        $this->handler = $handler;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        return $this->handler->fire($this, $this->argument('module'), $this->argument('model'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('module', InputArgument::REQUIRED, 'The name of module will be used.'),
			array('model', InputArgument::REQUIRED, 'The name of model will be created.'),
		);
	}

}
