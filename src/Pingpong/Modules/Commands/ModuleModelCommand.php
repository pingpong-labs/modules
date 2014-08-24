<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Pingpong\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Pingpong\Modules\Handlers\ModuleModelHandler;
use Symfony\Component\Console\Input\InputArgument;

class ModuleModelCommand extends Command {

	use ModuleCommandTrait;

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
        return $this->handler->fire($this, $this->getModuleName(), $this->argument('model'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('model', InputArgument::REQUIRED, 'The name of model will be created.'),
			array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
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
			array('fillable', null, InputOption::VALUE_OPTIONAL, 'The fillable attributes.', null),
		);
	}

}
