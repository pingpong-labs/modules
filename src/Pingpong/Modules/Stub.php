<?php namespace Pingpong\Modules;

class Stub {

    /**
     * The short stub name.
     *
     * @var string
     */
    protected $name;

    /**
     * The replacements array.
     *
     * @var array
     */
    protected $replaces = [];

    /**
     * The contructor.
     *
     * @param string $name
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
     * Get stub path.
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/Commands/stubs/' . $this->name . '.stub';
    }

    /**
     * Get stub contents.
     *
     * @return mixed|string
     */
    public function getContents()
    {
        $contents = file_get_contents($this->getStubPath());

        foreach ($this->replaces as $search => $replace)
        {
            $contents = str_replace('$' . strtoupper($search) . '$', $replace, $contents);
        }

        return $contents;
    }

    /**
     * Set replacements array.
     *
     * @param  array $replaces
     * @return $this
     */
    public function replace(array $replaces = [])
    {
        $this->replaces = $replaces;

        return $this;
    }

    /**
     * Get replacements.
     *
     * @return array
     */
    public function getReplaces()
    {
        return $this->replaces;
    }

    /**
     * Handle magic method __toString.
     *
     * @return mixed|string
     */
    public function __toString()
    {
        return $this->getContents();
    }

} 