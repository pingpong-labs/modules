<?php namespace Pingpong\Modules\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleUseCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:use';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Use the specified module.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
    {
    	$key     = $this->laravel['modules']->getModulesUsedNow();

        $session = $this->laravel['session.store'];
        
        $module  = Str::studly($this->argument('module'));

        if( ! $this->laravel['modules']->has($module))
        {
        	return $this->error("Module [{$module}] does not exists.");
        }

        if($session->has($key))
        {
        	$session->forget($key);
        }
    
    	$session->put($key, $module);

    	$this->info("Module [{$module}] has been used for current session.");
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
		);
	}

}
