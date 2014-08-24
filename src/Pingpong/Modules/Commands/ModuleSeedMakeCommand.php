<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Pingpong\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Pingpong\Modules\Handlers\ModuleSeedMakerHandler;

/**
 * Class ModuleSeedMakeCommand
 * @package Pingpong\Modules\Commands
 */
class ModuleSeedMakeCommand extends Command {

	use ModuleCommandTrait;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:seed-make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate new seeder for the specified module.';

    /**
     * @var ModuleSeedMakerHandler
     */
    protected $handler;

    /**
     * @param ModuleSeedMakerHandler $handler
     */
    public function __construct(ModuleSeedMakerHandler $handler)
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
        return $this->handler->fire($this, $this->getModuleName(), $this->argument('name'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('name', InputArgument::REQUIRED, 'The name of seeder will be created.'),
			array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
		);
	}

}
