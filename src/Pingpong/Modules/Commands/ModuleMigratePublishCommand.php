<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Pingpong\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Pingpong\Modules\Handlers\ModuleMigrationPublisherHandler;

/**
 * Class ModuleMigratePublishCommand
 * @package Pingpong\Modules\Commands
 */
class ModuleMigratePublishCommand extends Command {

	use ModuleCommandTrait;
	
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
     * @var ModuleMigrationPublisherHandler
     */
    protected $handler;

	/**
	 * Create a new command instance.
	 */
	public function __construct(ModuleMigrationPublisherHandler $handler)
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
        return $this->handler->fire($this, $this->getModuleName());
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
