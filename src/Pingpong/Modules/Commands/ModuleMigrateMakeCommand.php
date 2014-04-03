<?php namespace Pingpong\Modules\Commands;

use Pingpong\Modules\Module;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMigrateMakeCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:migrate:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate a new migration for the specified module.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Module $module, File $file)
	{
		$this->module  = $module;
		$this->file  = $file;
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->moduleName  		=  ucwords($this->argument('module'));
		$this->table 		 	=  str_plural(strtolower($this->argument('table')));
		$this->migrationName 	=  "Create".studly_case($this->table)."Table";

		if($this->module->has($this->moduleName))
		{
			$this->makeFile();
			$this->info("Created : ".$this->getDestinationFile());
			return $this->call('dump-autoload');
		}
		return $this->info("Module [$this->moduleName] does not exists.");
	}

	/**
	 * Get filename.
	 *
	 * @return string
	 */
	protected function getFilename()
	{
		return date("Y_m_d_His") . '_' . $this->migrationName.'.php';
	}

	/**
	 * Get fields.
	 *
	 * @return string
	 */
	protected function getFields()
	{
		$result = '';
		if($option = $this->option('fields'))
		{
			$fields = str_replace(" ", "", $option);
			$fields = explode(',', $fields);

			foreach ($fields as $field) {
				$result.= $this->setField($field);
			}
		}
		return $result;
	}

	/**
	 * Set field to script.
	 *
	 * @param  string  $option
	 * @return string
	 */
	protected function setField($option)
	{
		$result = '';
		if( ! empty($option) )
		{
			$option = explode(":", $option);
			$result.= '			$table->'.$option[1]."('$option[0]')";
			if(count($option) > 0)
			{
				foreach ($option as $key => $o) {
					if($key == 0 || $key == 1) continue;
					$result.= "->$o()";		
				}
			}
			$result.= ';'.PHP_EOL;
		}
		return $result;
	}

	/**
	 * Get destination file.
	 *
	 * @return string
	 */
	protected function getDestinationFile()
	{
		return $this->getPath() . $this->formatContent($this->getFilename());
	}

	/**
	 * Get seeder path.
	 *
	 * @return string
	 */
	protected function getPath()
	{
		$path = $this->module->getPath();
		return $path . "/$this->moduleName/database/migrations/";
	}

	/**
	 * Create new file.
	 *
	 * @return 	string
	 */
	public function makeFile()
	{
		return $this->file->put($this->getDestinationFile(), $this->getStubContent());
	}

	/**
	 * Get stub content by given key.
	 *
	 * @return string
	 */
	protected function getStubContent()
	{
		return $this->formatContent($this->file->get(__DIR__ . '/stubs/migration.stub'));
	}

	/**
	 * Replace the specified text from given content.
	 *
	 * @return string
	 */
	protected function formatContent($content)
	{
		return str_replace(
			['{{migrationName}}', '{{table}}', '{{fields}}'],
			[$this->migrationName, $this->table, $this->getFields()],
			$content
		);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('module', InputArgument::REQUIRED, 'The name of module will be created.'),
			array('table', InputArgument::REQUIRED, 'The name of table will be created.'),
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
			array('--fields', null, InputOption::VALUE_OPTIONAL, 'The specified fields table.', null),
		);
	}
}
