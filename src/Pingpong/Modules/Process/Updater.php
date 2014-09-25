<?php namespace Pingpong\Modules\Process;

class Updater extends Runner {

    /**
     * Update the dependencies for the specified module by given the module name.
     *
     * @param  string $module
     * @return void
     */
    public function update($module)
    {
        $packages = $this->module->prop($module . '::require', []);

        foreach ($packages as $name => $version)
        {
            $package = "\"{$name}:{$version}\"";

            $this->run("composer require {$package}");
        }
    }

}