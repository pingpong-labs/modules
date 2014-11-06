<?php namespace Pingpong\Modules;

use Illuminate\Support\Collection;

class Presenter {

	/**
	 * The \Pingpong\Modules\Module instance.
	 * 
	 * @var Module
	 */
	protected $module;

	/**
	 * The constructor.
	 * 
	 * @param Module $module
	 */
	public function __construct(Module $module)
	{
		$this->module = $module;
	}

	/**
	 * Get module json path for the specified module.
	 * 
	 * @return string
	 */
	public function getJsonFilePath()
	{
		return $this->module->getPath() . '/module.json';
	}

	/**
	 * Get module json content as Collection.
	 * 
	 * @return \Illuminate\Support\Collection
	 */
	public function json()
	{
		return Collection::make(json_decode(file_get_contents($this->getJsonFilePath()), true));
	}

	/**
	 * Get module description.
	 * 
	 * @return string
	 */
	public function getDescription()
	{
		return $this->get('description');
	}

	/**
	 * Get module keywords.
	 * 
	 * @return array
	 */
	public function getKeywords()
	{
		return $this->get('keywords');
	}

	/**
	 * Get module providers.
	 * 
	 * @return string
	 */
	public function getProviders()
	{
		return $this->get('providers');
	}

	/**
	 * Get module status.
	 * 
	 * @return string
	 */
	public function getStatus()
	{
		return (bool) $this->get('active');
	}

	/**
	 * Get a specified attribute from module.json file.
	 * 
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return $this->json()->get($key, $default);
	}

	/**
	 * Handle call to __get method.
	 * 
	 * @param  string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->get($key);
	}
	
}