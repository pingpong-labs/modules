<?php namespace Pingpong\Modules\Commands;

use Illuminate\Support\Str;
use Pingpong\Generators\Stub;
use Pingpong\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModelCommand extends GeneratorCommand {

    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new model for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('model', InputArgument::REQUIRED, 'The name of model will be created.'),
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
            array('fillable', null, InputOption::VALUE_OPTIONAL, 'The fillable attributes.', null),
        );
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        return new Stub('model', [
            'MODULE' => $this->getModuleName(),
            'NAME' => $this->getModelName(),
            'FILLABLE' => $this->getFillable()
        ]);
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $seederPath = $this->laravel['modules']->config('paths.generator.model');

        return $path . $seederPath . '/' . $this->getModelName() . '.php';
    }

    /**
     * @return mixed|string
     */
    private function getModelName()
    {
        return Str::studly($this->argument('model'));
    }

    /**
     * @return string
     */
    private function getFillable()
    {
        $fillable = $this->option('fillable');

        if ( ! is_null($fillable))
        {
            $arrays = explode(',', $fillable);

            return json_encode($arrays);
        }

        return '[]';
    }
}
