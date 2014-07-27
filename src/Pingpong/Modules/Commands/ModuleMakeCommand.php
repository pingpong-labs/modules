<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Pingpong\Modules\Handlers\ModuleGeneratorHandler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ModuleMakeCommand
 * @package Pingpong\Modules\Commands
 */
class ModuleMakeCommand extends Command {

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
     * @var ModuleGeneratorHandler
     */
    protected $handler;

    /**
     * @param ModuleGeneratorHandler $handler
     */
    public function __construct(ModuleGeneratorHandler $handler)
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
        return $this->handler->fire($this, $this->argument('name'));
	}


	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'The name of module will be created.'),
		);
	}

}
