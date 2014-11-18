<?php namespace Pingpong\Modules;

class Module {

	/**
	 * The module name.
	 * 
	 * @var string
	 */
	protected $name;

	/**
	 * The modules repository instance.
	 * 
	 * @var \Pingpong\Modules\Repository
	 */
	protected $repository;

	/**
	 * The constructor.
	 * @param string     $name
	 * @param Repository $repository
	 */
	public function __construct($name, Repository $repository)
	{
		$this->name = $name;
		$this->repository = $repository;
	}

	/**
	 * Set modules repository instance.
	 * 
	 * @param Repository $repository
	 */
	public function setRepository(Repository $repository)
	{
		$this->repository = $repository;

		return $this;
	}

	/**
	 * Get module repository instance.
	 * 
	 * @return Repository
	 */
	public function getRepository()
	{
		return $this->repository;
	}

	/**
	 * Getter for "name".
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get module name in lowercase.
	 * 
	 * @return string
	 */
	public function getLowerName()
	{
		return strtolower($this->name);
	}

	/**
	 * Get base path for the current module.
	 *
	 * @param  string|null $extra
	 * @return string
	 */
	public function getPath($extra = null)
	{
		if( ! is_null($extra)) return $this->getExtraPath($extra);

		return $this->repository->getModulePath($this->name);
	}

	/**
	 * Get extra path for specific module.
	 * 
	 * @return string
	 */
	public function getExtraPath($extra)
	{
		return $this->getPath() . '/' . $extra;
	}

	/**
	 * Delete module.
	 * 
	 * @return void
	 */
	public function delete()
	{
		$this->repository->getFiles()->deleteDirectory($this->getPath(), true);
	}

	/**
	 * Determinte whether the current module enabled.
	 * 
	 * @return bool
	 */
	public function active()
	{
		return $this->repository->active($this->name);
	}

	/**
	 * Enable the current module.
	 * 
	 * @return bool
	 */
	public function enable()
	{
		return $this->repository->enable($this->name);
	}

	/**
	 * Disable the current module.
	 * 
	 * @return bool
	 */
	public function disable()
	{
		return $this->repository->disable($this->name);
	}

	/**
	 * Determinte whether the current module disabled.
	 * 
	 * @return bool
	 */
	public function notActive()
	{
		return ! $this->active();
	}

	/**
	 * Get the module presenter class instance.
	 * 
	 * @return \Pingpong\Modules\Presenter
	 */
	public function present()
	{
		return new Presenter($this);
	}

	/**
	 * Get json data.
	 * 
	 * @return \Pingpong\Modules\Json
	 */
	public function json()
	{
		return Json::make($this->getJsonPath());
	}

	/**
	 * Get start filepath.
	 * 
	 * @return string
	 */
	public function getStartFilePath()
	{
		return $this->getPath() . '/start.php';
	}

	/**
	 * Get start json path.
	 * 
	 * @return string
	 */
	public function getJsonPath()
	{
		return $this->getPath() . '/modules.json';
	}

	/**
	 * Register the start file from current module.
	 * 
	 * @return string
	 */
	public function register()
	{
		include_once $this->getStartFilePath();
	}

	/**
	 * Handle call to __toString method.
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return $this->name;
	}

}
