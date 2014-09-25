<?php namespace Pingpong\Modules\Process;

use Pingpong\Modules\Module;
use Pingpong\Modules\Contracts\RunableInterface;

class Runner implements RunableInterface {

    /**
     * The module instance.
     *
     * @var \Pingpong\Modules\Module
     */
    protected $module;

    /**
     * The constructor.
     *
     * @param \Pingpong\Modules\Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * Run the given command.
     *
     * @param  string $command
     * @return void
     */
    public function run($command)
    {
        passthru($command);
    }

}