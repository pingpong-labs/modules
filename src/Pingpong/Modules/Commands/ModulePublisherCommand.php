<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;
use Pingpong\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Pingpong\Modules\Handlers\ModulePublisherHandler;

class ModulePublisherCommand extends Command {

	use ModuleCommandTrait;
	
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
	protected $description = 'Publish assets from the specified modules or from all modules.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(ModulePublisherHandler $handler)
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
			array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
		);
	}

}
