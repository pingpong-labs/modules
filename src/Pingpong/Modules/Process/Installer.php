<?php namespace Pingpong\Modules\Process;

class Installer extends Runner {

	/**
	 * Install the specified module by given name.
	 * 
	 * @param  string 		$name 
	 * @param  string|null 	$path 
	 * @return void       
	 */
	public function install($name, $path = null)
	{
        $this->run($this->getCommand($name, $path));
	}

	/**
	 * Get command.
	 * 
	 * @param  string 		$name 
	 * @param  string|null 	$path 
	 * @return string       
	 */
	protected function getCommand($name, $path = null)
	{
        $repoUrl = $this->getRepoPath($name);

        $path = $this->getModulePath($path);

        $gitPath = $this->getGitPath($name);

        return "cd {$path} && git clone {$repoUrl} && rm -rf {$gitPath}";
	}

	/**
	 * Get module path.
	 * 
	 * @param  string|null 	$path 
	 * @return string       
	 */
	protected function getModulePath($path = null)
	{
		return realpath($path ?: $this->module->getPath());
	}

	/**
	 * Get git path.
	 * 
	 * @param  string $name 
	 * @return string       
	 */
	protected function getGitPath($name)
	{
		return realpath($this->module->getModulePath(static::getModuleName($name)) . '/.git/');
	}

	/**
	 * Get repo path.
	 * 
	 * @param  string $name 
	 * @return string       
	 */
	protected function getRepoPath($name)
	{
		return "git@github.com:{$name}.git";
	}

    /**
     * Get module name for the given name.
     * 
     * @param  string $name 
     * @return mixed       
     */
    public static function getModuleName($name)
    {
        list($vendor, $module) = explode('/', $name);

        return $module;
    }

}