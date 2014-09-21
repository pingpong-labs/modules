<?php namespace Pingpong\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Pingpong\Modules\Stub;
use Pingpong\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Pingpong\Modules\Handlers\ModuleSeedMakerHandler;

/**
 * Class ModuleSeedMakeCommand
 * @package Pingpong\Modules\Commands
 */
class ModuleSeedMakeCommand extends GeneratorCommand {

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

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        return new Stub('seeder', [
            'NAME'      =>  $this->getSeederName(),
            'MODULE'    =>  $this->getModuleName()
        ]);
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $seederPath = $this->laravel['config']->get('modules::paths.generator.seeder');

        return $path . $seederPath . '/' . $this->getSeederName() . '.php';
    }

    /**
     * Get seeder name.
     * 
     * @return string 
     */
    private function getSeederName()
    {
        return Str::studly($this->argument('name')) . 'TableSeeder';
    }
}
