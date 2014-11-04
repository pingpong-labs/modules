<?php namespace Pingpong\Modules;

use Illuminate\Support\Collection;

class Presenter {

	protected $module;

	public function __construct(Module $module)
	{
		$this->module = $module;
	}

	public function getJsonFilePath()
	{
		return $this->module->getPath() . '/module.json';
	}

	public function json()
	{
		return Collection::make(json_decode(file_get_contents($this->getJsonFilePath()), true));
	}

	public function getDescription()
	{
		return $this->get('description');
	}

	public function getKeywords()
	{
		return $this->get('keywords');
	}

	public function getProviders()
	{
		return $this->get('providers');
	}

	public function getStatus()
	{
		return (bool) $this->get('active');
	}

	public function get($key, $default = null)
	{
		return $this->json()->get($key, $default);
	}

	public function __get($key)
	{
		return $this->get($key);
	}
	
}