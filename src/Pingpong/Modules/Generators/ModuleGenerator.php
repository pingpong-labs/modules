<?php namespace Pingpong\Modules\Generators;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\Repository as Config;

class ModuleGenerator extends Generator {

    protected $name;

    public function __construct($name, Config $config = null, Filesystem $files = null)
    {
        $this->name = Str::studly($name);
    }

    public function getName()
    {
        return $this->name;
    }

}