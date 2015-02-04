<?php namespace Pingpong\Modules\Process;

class Installer extends Runner {

    /**
     * Install the specified module by given name.
     *
     * @param  string $name
     * @param  string|null $path
     * @param bool $subtree
     * @return void
     */
    public function install($name, $path = null, $subtree)
    {
        if ($subtree)
        {
            $command = $this->getSubtreeCommand($name, $path);
        }
        else
        {
            $command = $this->getCommand($name);
        }

        $this->run($command);
    }

    /**
     * Get command.
     *
     * @param  string $name
     * @return string
     */
    protected function getCommand($name)
    {
        chdir(base_path());

        return "composer require \"$name\"";
    }

    /**
     * Get the git subtree command
     *
     * @param $name
     * @param $path
     * @return string
     */
    protected function getSubtreeCommand($name, $path)
    {
        $repoUrl = $this->getRepoPath($name);

        $moduleName = strtolower($this->getModuleName($name));

        $path = $this->getModulePathName($this->getModulePath($path)) . '/' . $this->getModuleName($name);

        return "git remote add {$moduleName} {$repoUrl} && git subtree add --prefix={$path} --squash {$moduleName} master";
    }

    /**
     * Get module path.
     *
     * @param  string|null $path
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
        return "https://github.com/{$name}.git";
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

    /**
     * Get module path name.
     *
     * @param  string $path
     * @return string
     */
    private function getModulePathName($path)
    {
        $parts = explode('/', $path);

        return array_last($parts, function ($key, $value)
        {
            return $value;
        });
    }
}