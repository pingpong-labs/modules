<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Pingpong\Modules\Handlers\ModuleSetupHandler;

/**
 * Class ModuleSetupCommand
 * @package Pingpong\Modules\Commands
 */
class ModuleSetupCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:setup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Setting up modules folders for first use.';

    /**
     * @var ModuleSetupHandler
     */
    protected $handler;

    /**
     * @param ModuleSetupHandler $handler
     */
    public function __construct(ModuleSetupHandler $handler)
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
        return $this->handler->fire($this);
	}
}
