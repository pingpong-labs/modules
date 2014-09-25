<?php namespace Pingpong\Modules\Commands;

use Pingpong\Modules\Stub;
use Illuminate\Support\Str;
use Pingpong\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class ModuleControllerCommand extends GeneratorCommand {

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

        $controllerPath = $this->laravel['config']->get('modules::paths.generator.controller');

        return $path . $controllerPath . '/' . $this->getControllerName() . '.php';
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $replaces = [
//            'APPNAME'       =>  trim($this->laravel['config']['namespaces.root'], '\\'),
            'MODULENAME' => $this->getModuleName(),
            'CONTROLLERNAME' => $this->getControllerName()

        ];
        return new Stub('controller', $replaces);
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
