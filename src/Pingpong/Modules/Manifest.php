<?php namespace Pingpong\Modules;

use Illuminate\Foundation\Application;

class Manifest
{	
	/**
	 * Application object
	 * 
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Filesystem object
	 * 
	 * @var Illuminate\Filesystem\Filesystem
	 */
	protected $file;

	/**
	 * Module object
	 * 
	 * @var Pingpong\Modules\Module
	 */
	protected $module;

	/**
	 * Module json file
	 * 
	 * @var string
	 */
	protected $jsonFile = 'module.json';

	public function __construct(Application $app)
	{
		$this->app 		= $app;
		$this->file 	= $this->app['files'];
	}

	/**
	 * Get module path
	 * 
	 * @return string 
	 */
	public function getModulePath()
	{
		return $this->app['module']->getPath(); 
	}

	/**
	 * Get JSON file from specified module
	 * 
	 * @param $module String
	 * @return object 
	 */
	public function getJsonFile($module)
	{
		return $this->getModulePath() . $module .'/'. $this->jsonFile;
	}

	/**
	 * Has json file ?
	 * 
	 * @param $module String
	 * @return boolean 
	 */
	public function hasJsonFile($module)
	{
		return $this->file->exists($this->getJsonFile($module));
	}

	/**
	 * Get JSON content from specified module
	 * 
	 * @param $module String
	 * @return object 
	 */
	public function getJsonContent($module)
	{
		$file = $this->getJsonFile($module);
		if($this->hasJsonFile($module))
		{
			return $this->file->get($file);
		}
		throw new Exception("Module [$module] doest not have module.json file. This file is required for each module.");
	}

	/**
	 * Convert JSON module detail to object
	 * 
	 * @param $module String
	 * @param $option Boolean
	 * @return object 
	 */
	public function parseJson($module, $option = FALSE)
	{
		return json_decode($this->getJsonContent($module), $option);
	}

	/**
	 * Get Detail from specified module
	 * 
	 * @param $module String
	 * @param $option Boolean
	 * @return object 
	 */
	public function getDetails($module, $option = FALSE)
	{
		return $this->parseJson($module, $option);
	}

}