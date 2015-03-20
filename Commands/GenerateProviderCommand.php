<?php namespace Pingpong\Modules\Commands;

use Illuminate\Support\Str;
use Pingpong\Generators\Stub;
use Pingpong\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateProviderCommand extends GeneratorCommand {

    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new service provider for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'The service provider name.'),
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
            array('master', null, InputOption::VALUE_NONE, 'Indicates the master service provider', null),
        );
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $stub = $this->option('master') ? 'scaffold/provider' : 'provider';

        return new Stub($stub, [
            'MODULE' => $this->getModuleName(),
            'LOWER_NAME' => strtolower($this->getModuleName()),
            'NAME' => $this->getFileName(),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace')
        ]);
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $generatorPath = $this->laravel['modules']->config('paths.generator.provider');

        return $path . $generatorPath . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }
}
