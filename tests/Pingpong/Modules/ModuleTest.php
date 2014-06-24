<?php namespace Pingpong\Modules;

use Mockery;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    function getModuleInstance()
    {
        $app = Mockery::mock('Illuminate\Foundation\Application');
        $finder = Mockery::mock('Pingpong\Modules\ModuleFinder');

        return new Module($app, $finder);
    }

    function getTmpPath()
    {
        return __DIR__ . '/../Modules/';
    }

    function testItInitialize()
    {
        $this->assertInstanceOf('Pingpong\Modules\Module', $this->getModuleInstance());
    }

    function testTmpFolderExists()
    {
        $this->assertTrue(is_dir($this->getTmpPath()));
    }

}