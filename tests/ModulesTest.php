<?php

use Mockery as m;
use Pingpong\Modules\Module;

class ModulesTest extends PHPUnit_Framework_TestCase {
	
	public function tearDown()
	{
		m::close();
	}

	protected function getPath()
	{
		return __DIR__ . '/../../public';
	}

	public function setUp()
	{
        $this->finder = m::mock('Pingpong\Modules\Finder');
        $this->config = m::mock('Illuminate\Config\Repository');
        $this->view = m::mock('Illuminate\View\Factory');
        $this->lang = m::mock('Illuminate\Translation\Translator');
        $this->files  = m::mock('Illuminate\Filesystem\Filesystem');

        $this->module = new Module($this->finder, $this->config, $this->view, $this->lang, $this->files);
	}

	public function testInitialize()
	{
		$this->assertInstanceOf('Pingpong\Modules\Module', $this->module);
	}

	public function testGetAllModules()
	{
		$this->finder->shouldReceive('all')->once()->andReturn(['default']);

        $modules = $this->module->all();

        $this->assertTrue(is_array($modules));
        $this->assertArrayHasKey(0, $modules);
        $this->assertArrayNotHasKey(1, $modules);
	}

	public function testHasModule()
	{
		$this->finder->shouldReceive('all')->once()->andReturn(['users', 'posts']);

		$hasModule = $this->module->has('users');

		$this->assertTrue($hasModule);
	}

	public function testIgnoreHiddenModule()
	{
		$this->finder->shouldReceive('setPath')->once()->with($this->getPath());
		$this->finder->shouldReceive('all')->once()->andReturn(['.users', 'posts']);

		$this->module->setPath($this->getPath());

		$this->assertEquals(2, $this->module->count());
	}
} 