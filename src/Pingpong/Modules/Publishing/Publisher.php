<?php namespace Pingpong\Modules\Publishing;

use Pingpong\Modules\Module;
use Illuminate\Console\Command;
use Pingpong\Modules\Repository;
use Illuminate\Filesystem\Filesystem;
use Pingpong\Modules\Contracts\PublisherInterface;

abstract class Publisher implements PublisherInterface {

	protected $module;

	protected $repository;

	protected $console;

	protected $success = '';

	protected $error = '';

	public function __construct($module)
	{
		$this->module = $module;
	}

	public function getModule()
	{
		return new Module($this->module, $this->getRepository());
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

	public function setConsole(Command $console)
	{
		$this->console = $console;

		return $this;
	}

	public function getConsole()
	{
		return $this->console;
	}

	public function getFilesystem()
	{
		return $this->repository->getFiles();
	}

	public function info($message)
	{
		$this->console->info($message);
	}

	public function error($message)
	{
		$this->console->error($message);
	}

	abstract public function getDestinationPath();

	abstract public function getSourcePath();

	public function publish()
	{
		if( ! $this->console instanceof Command)
		{
			$message = "The 'console' property must instance of \Illuminate\Console\Command.";

			throw new \RuntimeException($message);
		}

		if( ! $this->getFilesystem()->isDirectory($sourcePath = $this->getSourcePath()))
		{
			$message = "Source path does not exist : {$sourcePath}";

			throw new \InvalidArgumentException($message);
		} 

		if( ! $this->getFilesystem()->isDirectory($destinationPath = $this->getDestinationPath()))
		{
			$message = "Destination path does not exist : {$destinationPath}";

			throw new \InvalidArgumentException($message);
		} 

		if($this->getFilesystem()->copyDirectory($sourcePath, $destinationPath))
		{
			$this->info($this->success);
		}
		else
		{
			$this->error($this->error);
		}
	}

}