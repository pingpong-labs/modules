<?php  namespace Pingpong\Modules;

/**
 * Class Stub
 * @package Pingpong\Modules
 */
class Stub {

    /**
     * @var
     */
    protected $name;

    /**
     * @var array
     */
    protected $replaces = [];

    /**
     * @param $name
     * @param array $replaces
     */
    public function __construct($name, array $replaces = [])
    {
        $this->name = $name;
        $this->replaces = $replaces;
    }

    /**
     * Create new self instance.
     * 
     * @param  string $name     
     * @param  array $replaces 
     * @return self           
     */
    public static function create($name, array $replaces = [])
    {
        return new static($name, $replaces);
    }

    /**
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/Commands/stubs/' . $this->name . '.stub';
    }

    /**
     * @return mixed|string
     */
    public function getContents()
    {
        $contents =  file_get_contents($this->getStubPath());

        foreach($this->replaces as $search => $replace)
        {
            $contents = str_replace('$'. strtoupper($search) . '$', $replace, $contents);
        }

        return $contents;
    }

    /**
     * @param array $replaces
     * @return $this
     */
    public function replace(array $replaces = [])
    {
        $this->replaces = $replaces;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function __toString()
    {
        return $this->getContents();
    }

} 