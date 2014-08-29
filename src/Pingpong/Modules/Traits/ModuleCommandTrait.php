<?php namespace Pingpong\Modules\Traits;

use Illuminate\Support\Str;

trait ModuleCommandTrait {
	
	/**
	 * Get the module name.
	 * 
	 * @return string
	 */
	public function getModuleName()
	{
		$module = $this->argument('module') ?: $this->laravel['modules']->getUsedNow();

		return Str::studly($module);
	}

}