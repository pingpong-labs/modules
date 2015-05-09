<?php namespace Pingpong\Modules\Commands;

use Illuminate\Support\Str;
use Pingpong\Generators\Stub;
use Pingpong\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class GenerateFilterCommand extends GeneratorCommand {

    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-middleware';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new filter for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'The name of the command.'),
            array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
        );
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        return (new Stub('/filter.stub', [
            'MODULE' => $this->getModuleName(),
            'NAME' => $this->getFileName(),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace')
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $seederPath = $this->laravel['modules']->config('paths.generator.filter');

        return $path . $seederPath . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

}
