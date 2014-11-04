<?php namespace Pingpong\Modules;

class Module {

	protected $name;

	protected $repository;

	public function __construct($name, $repository = null)
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
		return strtolower($name);
	}

	public function getPath()
	{
		return $this->repository->getModulePath($this->name);
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

	public function __toString()
	{
		return $this->name;
	}

}
