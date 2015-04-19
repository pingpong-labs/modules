<?php namespace Pingpong\Modules\Commands;

use Pingpong\Generators\Stub;
use Pingpong\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class GenerateRouteProviderCommand extends GeneratorCommand {

    use ModuleCommandTrait;

    protected $name = 'module:route-provider';

    protected $description = 'Generate a new route service provider for the specified module.';

    protected function getArguments()
    {
        return array(
            array('module', InputArgument::OPTIONAL, 'The name of module will be used.'),
        );
    }

    /**
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        return (new Stub('/route-provider.stub', [
            'MODULE' => $this->getModuleName(),
            'NAME' => $this->getFileName(),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace')
        ]))->render();
    }

    /**
     * Get the destination file path.
     *
     * @return string
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
        return 'RouteServiceProvider';
    }
}
