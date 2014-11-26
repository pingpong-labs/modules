<?php namespace Tests\Pingpong\Modules;

class CliTest extends TestCase {

	public function testGenerators()
	{
		$this->cleanup();
		
		$commandOptions = [
			'module:setup' => [],
			'module:make' => ['name' => 'Bar'],
			'module:model' => ['model' => 'Bazz', 'module' => 'Bar'],
			'module:use' => ['module' => 'Bar'],
 			'module:controller' => ['controller' => 'FooController'],
 			'module:command' => ['name' => 'FooCommand'],
 			'module:disable' => ['module' => 'Bar'],
 			'module:enable' => ['module' => 'Bar'],
 			'module:provider' => ['name' => 'ConsoleServiceProvider'],
 			'module:publish' => ['module' => 'Bar'],
 			'module:publish-migration' => ['module' => 'Bar'],
 			// 'module:migration' => ['name' => 'create_users_table', '--fields' => ''],
		];

		foreach ($commandOptions as $command => $options)
		{
			$this->app['artisan']->call($command, $options);
		}

		$this->cleanup();
	}

	public function cleanup()
	{
		$module = $this->app['modules']->get('Bar');

		if($module)
		{
			$module->delete();

			rmdir($module->getPath());
		}
	}

}