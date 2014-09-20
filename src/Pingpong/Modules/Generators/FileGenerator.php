<?php namespace Pingpong\Modules\Generators;

use Illuminate\Filesystem\Filesystem;
use Pingpong\Modules\Exceptions\FileAlreadyExistsException;

/**
 * Class FileGenerator
 * @package Pingpong\Modules\Generators
 */
class FileGenerator extends Generator {

    /**
     * @var
     */
    protected $path;

    /**
     * @var
     */
    protected $contents;

    /**
     * @var null
     */
    protected $filesystem;

    /**
     * @param $path
     * @param $contents
     * @param null $filesystem
     */
    public function __construct($path, $contents, $filesystem = null)
    {
        $this->path = $path;
        $this->contents = $contents;
        $this->filesystem = $filesystem ?: new Filesystem;
    }

    /**
     * @return mixed
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param mixed $contents
     * @return $this
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * @return null
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @param null $filesystem
     * @return $this
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Generate the file.
     */
    public function generate()
    {
        if( ! $this->filesystem->exists($path = $this->getPath()))
        {
            return $this->filesystem->put($path, $this->getContents());
        }

        throw new FileAlreadyExistsException;
    }

} 