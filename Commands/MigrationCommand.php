<?php namespace Pingpong\Modules\Commands;

use Illuminate\Support\Str;
use Pingpong\Generators\Exceptions\InvalidMigrationName;
use Pingpong\Generators\Schema\Field;
use Pingpong\Generators\Schema\Parser;
use Pingpong\Generators\Stub;
use Pingpong\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrationCommand extends GeneratorCommand {

    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new migration for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'The migration name will be created.'),
            array('module', InputArgument::OPTIONAL, 'The name of module will be created.'),
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
            array('fields', null, InputOption::VALUE_OPTIONAL, 'The specified fields table.', null),
            array('plain', null, InputOption::VALUE_NONE, 'Create plain migration.'),
        );
    }

    /**
     * @throws InvalidMigrationName
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $schema = new Parser($this->argument('name'));

        $fields = new Field($this->option('fields'));

        if ($this->option('plain'))
        {
            return new Stub('migration/plain', [
                'CLASS' => $this->getClassName()
            ]);
        }
        elseif ($schema->isCreate())
        {
            return new Stub('migration/create', [
                'CLASS' => $this->getClassName(),
                'FIELDS' => $fields->getSchemaCreate(),
                'TABLE' => $schema->getTableName()
            ]);
        }
        elseif ($schema->isAdd())
        {
            return new Stub('migration/add', [
                'CLASS' => $this->getClassName(),
                'FIELDS_UP' => $fields->getSchemaCreate(),
                'FIELDS_DOWN' => $fields->getSchemaDropColumn(),
                'TABLE' => $schema->getTableName()
            ]);
        }
        elseif ($schema->isDelete())
        {
            return new Stub('migration/delete', [
                'CLASS' => $this->getClassName(),
                'FIELDS_DOWN' => $fields->getSchemaCreate(),
                'FIELDS_UP' => $fields->getSchemaDropColumn(),
                'TABLE' => $schema->getTableName()
            ]);
        }
        elseif ($schema->isDrop())
        {
            return new Stub('migration/drop', [
                'CLASS' => $this->getClassName(),
                'FIELDS' => $fields->getSchemaCreate(),
                'TABLE' => $schema->getTableName()
            ]);
        }

        throw new InvalidMigrationName;
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $generatorPath = $this->laravel['modules']->config('paths.generator.migration');

        return $path . $generatorPath . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return date('Y_m_d_His_') . $this->getSchemaName();
    }

    /**
     * @return array|string
     */
    private function getSchemaName()
    {
        return $this->argument('name');
    }

    /**
     * @return string
     */
    private function getClassName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Run the command.
     */
    public function fire()
    {
        parent::fire();

        $this->call('dump-autoload');
    }

}
