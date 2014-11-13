<?php namespace Pingpong\Modules;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Contracts\ArrayableInterface;

class Json implements ArrayableInterface {

	/**
	 * The file path.
	 * 
	 * @var string
	 */
	protected $path;
	
	/**
	 * The laravel filesystem instance.
	 * 
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $filesystem;

	/**
	 * The constructor.
	 * 
	 * @param mixed $path
	 * @param \Illuminate\Filesystem\Filesystem $filesystem
	 */
	public function __construct($path, Filesystem $filesystem = null)
	{
		$this->path = (string) $path;
		$this->filesystem = $filesystem ?: new Filesystem;
	}

    /**
     * Get filesystem.
     *
     * @return mixed
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Set filesystem.
     *
     * @param  null $filesystem
     * @return $this
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * Get path.
     * 
     * @return string
     */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Set path.
	 * 
	 * @param mixed $path
	 */
	public function setPath($path)
	{
		$this->path = (string) $path;

		return $this;
	}

	/**
	 * Make new instance.
	 * 
	 * @param  string $path
	 * @return static
	 */
	public static function make($path)
	{
		return new static($path);
	}

	/**
	 * Get file content.
	 * 
	 * @return string
	 */
	public function getContents()
	{
		return $this->filesystem->get($this->getPath());
	}

	/**
	 * Get file contents as array.
	 * 
	 * @return array
	 */
	public function toArray()
	{
		return json_decode($this->getContents, 1);
	}

	/**
	 * Get file contents as laravel collection.
	 * 
	 * @return \Illuminate\Support\Collection
	 */
	public function toCollection()
	{
		return Collection::make($this->toArray());
	}

	/**
	 * Get a specific key from collection.
	 * 
	 * @param  string $key
	 * @param  null|mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return $this->toCollection()->get($key, $default);
	}

	/**
	 * Handle magic method __get.
	 * 
	 * @param  string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->get($key);
	}

}