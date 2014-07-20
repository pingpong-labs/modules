<?php

use Mockery as m;
use Pingpong\Modules\Finder;

class FinderTest extends PHPUnit_Framework_TestCase
{
    function tearDown()
    {
        m::close();
    }

    protected function getPath()
    {
        return __DIR__ . '/../public/';
    }

    function testGetAllModules()
    {
        $config = m::mock('Illuminate\Config\Repository');
        $files  = m::mock('Illuminate\Filesystem\Filesystem');

        $finder = new Finder($files, $config);
        $finder->setPath($this->getPath());

        $files->shouldReceive('directories')->once()->andReturn(array('Blog'));

        $all = $finder->all();

        $this->assertTrue(is_array($all));
        $this->assertArrayHasKey(0, $all);
        $this->assertArrayNotHasKey(1, $all);
    }

    function testHasAModule()
    {
        $config = m::mock('Illuminate\Config\Repository');
        $files  = m::mock('Illuminate\Filesystem\Filesystem');

        $finder = new Finder($files, $config);
        $finder->setPath($this->getPath());

        $files->shouldReceive('directories')->times(2)->andReturn(['default']);

        $this->assertTrue($finder->has('default'));
        $this->assertNotTrue($finder->has('white'));
    }

    function testSetModulePath()
    {
        $config = m::mock('Illuminate\Config\Repository');
        $files  = m::mock('Illuminate\Filesystem\Filesystem');

        $finder = new Finder($files, $config);

        $theFinder = $finder->setPath($this->getPath());

        $this->assertInstanceOf('Pingpong\Modules\Finder', $theFinder);
        $this->assertInstanceOf('Pingpong\Modules\Finder', $finder);
    }

    function testGetModulePath()
    {
        $config = m::mock('Illuminate\Config\Repository');
        $files  = m::mock('Illuminate\Filesystem\Filesystem');

        $finder = new Finder($files, $config);

        $config->shouldReceive('get')->times(3)->andReturn($this->getPath());
        $files->shouldReceive('directories')->times(2)->andReturn(['default']);

        $modulePath1 = $finder->getModulePath('default');
        $modulePath2 = $finder->getModulePath('white');

        $this->assertEquals($modulePath1, $this->getPath() . "/default");
        $this->assertNull($modulePath2);
    }
}