<?php namespace Pingpong\Modules\Commands;

use Pingpong\Generators\Stub;
use Pingpong\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class ControllerCommand extends GeneratorCommand {

    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new restful controller for the specified module.';

    /**
     * Get controller name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $controllerPath = $this->laravel['modules']->config('paths.generator.controller');

        return $path . $controllerPath . '/' . $this->getControllerName() . '.php';
    }

    /**
     * @return Stub
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return new Stub('controller', [
            'MODULENAME' => $module->getStudlyName(),
            'CONTROLLERNAME' => $this->getControllerName(),
            'NAMESPACE' => $module->getLowername()
        ]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('controller', InputArgument::REQUIRED, 'The name of the controller class.'),
            array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
        );
    }

    /**
     * @return array|string
     */
    protected function getControllerName()
    {
        $controller = studly_case($this->argument('controller'));

        if ( ! str_contains(strtolower($controller), 'controller'))
        {
            $controller = $controller . 'Controller';
        }

        return $controller;
    }
}
