<?php namespace Pingpong\Modules\Traits;

use Illuminate\Support\Str;

trait ModuleCommandTrait {
	
	public function getModuleName()
	{
		$module = $this->argument('module') ?: $this->laravel['modules']->getUsedNow();

		$module = Str::studly($module);

        if( ! $this->laravel['modules']->has($module))
        {
            $this->error("Module [{$module}] does not exists.");

            exit;
        }

        return $module;
	}

}