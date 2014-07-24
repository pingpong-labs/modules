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
        $this->html = m::mock('Illuminate\Html\HtmlBuilder');
        $this->url = m::mock('Illuminate\Routing\UrlGenerator');

        $this->module = new Module($this->finder, $this->config, $this->view, $this->lang, $this->files, $this->html, $this->url);
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

    public function testGetModulePath()
    {
        $this->config->shouldReceive('get')->once()->with('modules::paths.modules')->andReturn('foobar');
        $this->assertEquals('foobar', $this->module->getPath());
    }

    public function testGetAssetPath()
    {
        $this->config->shouldReceive('get')->once()->with('modules::paths.assets')->andReturn('foo');
        $this->assertEquals('foo', $this->module->getAssetsPath());
    }

    public function testGenerateAssetUrl()
    {
        $this->config->shouldReceive('get')->once()->with('modules::paths.assets')->andReturn('foo');
        $this->url->shouldReceive('asset')->once()->andReturn('baz');

        $url = $this->module->asset('blog', 'img/foo.png');
        $this->assertEquals('baz', $url); 
    }

    public function testGenerateScriptTag()
    {
        $this->config->shouldReceive('get')->once()->with('modules::paths.assets')->andReturn('foo');    
        $this->url->shouldReceive('asset')->once();
        $this->html->shouldReceive('attributes')->once();

        $tag = $this->module->script('blog', 'js/all.js');
        
        $this->assertEquals('<script></script>' . PHP_EOL, $tag);
    }

    public function testGenerateStyleTag()
    {
        $this->config->shouldReceive('get')->once()->with('modules::paths.assets')->andReturn('foo');    
        $this->url->shouldReceive('asset')->once();
        $this->html->shouldReceive('attributes')->once();

        $tag = $this->module->style('blog', 'css/all.css');
        
        $this->assertEquals('<link>' . PHP_EOL, $tag);  
    }
} 
