<?php namespace Pingpong\Modules\Process;

use Pingpong\Modules\Repository;
use Pingpong\Modules\Contracts\RunableInterface;

class Runner implements RunableInterface {

    /**
     * The module instance.
     *
     * @var \Pingpong\Modules\Repository
     */
    protected $module;

    /**
     * The constructor.
     *
     * @param \Pingpong\Modules\Repository $module
     */
    public function __construct(Repository $module)
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