<?php namespace Pingpong\Modules\Traits;

use Illuminate\Support\Str;

trait ModuleCommandTrait {
	
	public function getModuleName()
	{
		$module = $this->argument('module') ?: $this->laravel['modules']->getUsedNow();

		return Str::studly($module);
	}

}