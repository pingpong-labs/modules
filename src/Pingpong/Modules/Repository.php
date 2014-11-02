<?php namespace Pingpong\Modules;

use Countable;
use Pingpong\Modules\Process\Updater;
use Pingpong\Modules\Process\Installer;

class Repository extends Finder {

    /**
     * Get all enabled (status = 1) or disabled (status = 0) modules
     *
     * @param int $status
     * @return array
     */
    public function getByStatus($status = 1)
    {
        $data = array();

        foreach ($this->all() as $module)
        {
            if ($status == 1)
            {
                if ($this->active($module))
                {
                    $data[] = $module;
                }
            }
            else
            {
                if ($this->notActive($module))
                {
                    $data[] = $module;
                }
            }
        }

        return $data;
    }

    /**
     * Return all enabled modules
     *
     * @return array
     */
    public function enabled()
    {
        return $this->getByStatus(1);
    }

    /**
     * Return all disabled modules
     *
     * @return array
     */
    public function disabled()
    {
        return $this->getByStatus(0);
    }

    /**
     * Determine if the module exists.
     *
     * @param    string $name
     * @return    string
     */
    public function has($name)
    {
        return $this->exists($name);
    }

    /**
     * Get count of all modules.
     *
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * Determine if the module exists.
     *
     * @param    string $name
     * @return    string
     */
    public function exists($name)
    {
        return in_array($name, $this->all());
    }

    /**
     * Update dependencies for the specified module.
     *
     * @param  string $module
     * @return void
     */
    public function update($module)
    {
        with(new Updater($this))->update($module);
    }

    /**
     * Install the specified module.
     *
     * @param  string $name
     * @param  string $path
     * @param bool $subtree
     * @return void
     */
    public function install($name, $path = null, $subtree = false)
    {
        with(new Installer($this))->install($name, $path, $subtree);
    }

    /**
     * Alias for "property" method.
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function prop($key, $default = null)
    {
        return $this->property($key, $default);
    }

    /**
     * Check if a given module active.
     *
     * @param $module
     * @return bool
     */
    public function active($module)
    {
        return $this->prop("{$module}::active") == 1;
    }

    /**
     * Check if a given module not active.
     *
     * @param $module
     * @return bool
     */
    public function notActive($module)
    {
        return ! $this->active($module);
    }

    /**
     * Get modules used now.
     *
     * @return string
     */
    public function getUsedNow()
    {
        return $this->finder->getUsed();
    }


}