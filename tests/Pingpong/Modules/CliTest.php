<?php namespace Tests\Pingpong\Modules;

class CliTest extends TestCase {

	public function testCommands()
	{
		$this->cleanup();
		
		$commandOptions = [
			'module:setup' => [],
			'module:make' => ['name' => ['Bar']],
			'module:model' => ['model' => 'Baz', 'module' => 'Bar'],
			'module:use' => ['module' => 'Bar'],
 			'module:controller' => ['controller' => 'FooController'],
 			'module:command' => ['name' => 'FooCommand'],
 			'module:disable' => ['module' => 'Bar'],
 			'module:enable' => ['module' => 'Bar'],
 			'module:provider' => ['name' => 'ConsoleServiceProvider'],
 			'module:publish' => ['module' => 'Bar'],
 			'module:publish-migration' => ['module' => 'Bar'],
 			'module:install' => ['name' => 'pingpong-modules/hello', 'version' => 'dev-master'],
		];

		foreach ($commandOptions as $command => $options)
		{
			$this->app['artisan']->call($command, $options);
		}

		$this->cleanup();
	}

	public function cleanup()
	{
		$modules = ['Bar', 'Hello'];

		foreach ($modules as $name)
		{	
			$module = $this->app['modules']->get($name);

			if($module)
			{
				$module->delete();

				rmdir($module->getPath());
			}
		}
	}

}