<?php

namespace Pingpong\Modules\Generators;

<<<<<<< HEAD
use Illuminate\Foundation\Application;
=======
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModuleMigrateMake extends Command {

	/**
<<<<<<< HEAD
	 * Application object
	 * 
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;

	/**
=======
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:migrate-make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new migration file for specified module.';

	/**
	 * Create a new command instance.
<<<<<<< HEAD
	 * 
	 * @param 	$app 	Illuminate\Foundation\Application
	 * @return 	void
	 */
	public function __construct(Application $app)
	{
		parent::__construct();
		$this->app = $app;
=======
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$fields = $this->option('fields');

<<<<<<< HEAD
		$module = strtolower($this->argument('module'));
		// $module = ucwords($module);

		$table = $this->argument('name');
=======
		$module = strtolower($this->argument('name'));
		// $module = ucwords($module);

		$table = $this->argument('migration');
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
		$table = strtolower($table);

		$classname = "Create".ucwords($table)."Table";
		$filename = date("Y_m_d_His")."_create_".$table."_table.php";

<<<<<<< HEAD
		$path = $this->app['module']->getDirName();
=======
		$path = \Config::get('modules::module.directory');
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
		$path.= '/'.$module.'/database/migrations/';

		if(!is_dir($path))
		{
			$this->error("Module $module not exists.");
			return;
		}

		$template = __DIR__.'/templates/migration.txt';
<<<<<<< HEAD
		if(!$this->app['files']->exists($template))
=======
		if(!file_exists($template))
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
		{
			throw new \Exception("Migration template does not exists!");
		}

		$search = array(
			'{{module}}',		// module name
			'{{classname}}',	// class name
			'{{table}}',		// table name
			'{{fields}}',		// fields
		);

		$replace = array(
			$module,
			$classname,
			$table,
			$this->fetchFields($fields)
		);

<<<<<<< HEAD
		$script = $this->app['files']->get($template); 
=======
		$script = file_get_contents($template); 
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
		$script = str_replace($search, $replace, $script);
		
		$this->info("Creating migration for module $module.");

<<<<<<< HEAD
		if( ! $this->app['files']->put($path.$filename, $script))
=======
		if( ! file_put_contents($path.$filename, $script))
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
		{
			$this->error("Can not create migration : ".$filename);;
		}else{
			$this->info("Created migration for module $module : ". $filename);
		}
	}

	/**
	 * Fetch --fields as script.
	 *
	 * @return string
	 */
	protected function fetchFields($argument = null)
	{
		$result = '';
		if( ! empty($argument) )
		{
			$fields = str_replace(" ", "", $argument);
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
					if($key == 0 OR $key == 1) continue;
					$result.= "->$o()";		
				}
			}
			$result.= ';'.PHP_EOL;
		}
		return $result;
	}


	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
<<<<<<< HEAD
			array('module', InputArgument::REQUIRED, 'Module name.'),
			array('name', InputArgument::REQUIRED, 'Migration name.'),
=======
			array('name', InputArgument::REQUIRED, 'Module name.'),
			array('migration', InputArgument::REQUIRED, 'Migration name.'),
>>>>>>> 64bfadd8092e97785a144a4b1db7f18e77dc9199
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
			array('--fields', null, InputOption::VALUE_OPTIONAL, 'Specified fields table.', null),
		);
	}

}
