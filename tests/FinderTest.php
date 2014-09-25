<?php

use Mockery as m;
use Pingpong\Modules\Finder;

class FinderTest extends PHPUnit_Framework_TestCase {
    protected $config;
    protected $files;
    protected $finder;

    function tearDown()
    {
        m::close();
    }

    function setUp()
    {
        $this->config = m::mock('Illuminate\Config\Repository');
        $this->files = m::mock('Illuminate\Filesystem\Filesystem');

        $this->finder = new Finder($this->files, $this->config);
        $this->finder->setPath($this->getPath());
    }

    protected function getPath()
    {
        return realpath(__DIR__ . '/../public');
    }

    function testGetAllModules()
    {
        $this->files->shouldReceive('directories')->once()->andReturn(array('Blog'));

        $all = $this->finder->all();

        $this->assertTrue(is_array($all));
        $this->assertArrayHasKey(0, $all);
        $this->assertArrayNotHasKey(1, $all);
    }

    function testHasAModule()
    {
        $this->files->shouldReceive('directories')->times(2)->andReturn(array('default'));

        $this->assertTrue($this->finder->has('default'));
        $this->assertNotTrue($this->finder->has('white'));
    }

    function testSetModulePath()
    {
        $theFinder = $this->finder->setPath($this->getPath());

        $this->assertInstanceOf('Pingpong\Modules\Finder', $theFinder);
        $this->assertEquals($this->getPath(), $theFinder->getPath());
    }

    function testGetModulePath()
    {
        $this->files->shouldReceive('directories')->times(2)->with($this->getPath())->andReturn(array('Blog'));

        $path1 = $this->finder->getModulePath('Blog');
        $path2 = $this->finder->getModulePath('default');

        $this->assertEquals($this->getPath() . '/Blog/', $path1);
        $this->assertNull($path2);
    }
}