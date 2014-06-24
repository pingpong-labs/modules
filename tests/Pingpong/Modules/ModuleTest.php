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

    function testItInitialize()
    {
        $this->assertInstanceOf('Pingpong\Modules\Module', $this->getModuleInstance());
    }
}