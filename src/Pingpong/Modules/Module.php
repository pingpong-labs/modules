<?php namespace Pingpong\Modules;

class Module {

	protected $name;

	protected $repository;

	public function __construct($name, Repository $repository)
	{
		$this->name = $name;
		$this->repository = $repository;
	}

	public function setRepository(Repository $repository)
	{
		$this->repository = $repository;

		return $this;
	}

	public function getRepository()
	{
		return $this->repository;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getLowerName()
	{
		return strtolower($this->name);
	}

	public function getPath()
	{
		return $this->repository->getModulePath($this->name);
	}

	public function delete()
	{
		$this->repository->getFiles()->deleteDirectory($this->getPath(), true);
	}

	public function active()
	{
		return $this->repository->active($this->name);
	}

	public function enable()
	{
		return $this->repository->enable($this->name);
	}

	public function disable()
	{
		return $this->repository->disable($this->name);
	}

	public function notActive()
	{
		return ! $this->active();
	}

	public function present()
	{
		return new Presenter($this);
	}

	public function __toString()
	{
		return $this->name;
	}

	public function getStartFilePath()
	{
		return $this->getPath() . '/start.php';
	}

	public function register()
	{
		include_once $this->getStartFilePath();
	}

}
