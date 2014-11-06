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
            array('module', InputArgument::REQUIRED, 'The name of module will be used.'),
        );
    }

    /**
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        return new Stub('route-provider', [
            'MODULE' => $this->getModuleName(),
            'NAME' => $this->getFileName()
        ]);
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $generatorPath = $this->laravel['config']->get('modules::paths.generator.provider');

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
