<?php namespace Pingpong\Modules\Schema;

use Pingpong\Modules\Exceptions\InvalidMigrationName;

class Parser {

    /**
     * The migration name.
     *
     * @var string
     */
    protected $name;

    /**
     * The array data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * The available schema actions.
     *
     * @var array
     */
    protected $actions = [
        'create' => [
            'create',
            'make'
        ],
        'delete' => [
            'delete',
            'remove'
        ],
        'add' => [
            'add',
            'update',
            'append',
            'insert'
        ],
        'drop' => [
            'destroy',
            'drop'
        ]
    ];

    /**
     * The constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->data = $this->fetchData();
    }

    /**
     * Get original migration name.
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->name;
    }

    /**
     * Get schema type or action.
     *
     * @return string
     */
    public function getAction()
    {
        return head($this->data);
    }

    /**
     * Get the table will be used.
     *
     * @return mixed|null
     * @throws InvalidMigrationName
     */
    public function getTableName()
    {
        $table = null;

        if ($this->isCreate())
        {
            $table = array_get($this->data, 1);
        }
        elseif ($this->isDrop())
        {
            $table = array_get($this->data, 1);
        }
        elseif ($this->isAdd())
        {
            $table = array_get($this->getMatchesWithAddSchema(), 2);
        }
        elseif ($this->isDelete())
        {
            $table = array_get($this->getMatchesWithDeleteSchema(), 2);
        }

        if ( ! is_null($table))
        {
            return $table;
        }

        throw new InvalidMigrationName;
    }

    /**
     * Get the name of column will be altered.
     *
     * @throws InvalidMigrationName
     * @return string
     */
    public function getAlterColumn()
    {
        $table = null;

        if ($this->isAdd())
        {
            $table = array_get($this->getMatchesWithAddSchema(), 1);
        }
        elseif ($this->isDelete())
        {
            $table = array_get($this->getMatchesWithDeleteSchema(), 1);
        }

        if ( ! is_null($table))
        {
            return $table;
        }

        throw new InvalidMigrationName;

    }

    /**
     * Get the matches data when using add schema.
     *
     * @return array
     */
    public function getMatchesWithAddSchema()
    {
        $matches = [];

        foreach ($this->actions['add'] as $action)
        {
            if ($this->is($action))
            {
                preg_match("/{$action}_(.*)_to_(.*)_table/", $this->name, $matches);
            }
        }

        return $matches;
    }

    /**
     * Get the matches data when using delete schema.
     *
     * @return array
     */
    public function getMatchesWithDeleteSchema()
    {
        $matches = [];

        foreach ($this->actions['delete'] as $action)
        {
            if ($this->is($action))
            {
                preg_match("/{$action}_(.*)_from_(.*)_table/", $this->name, $matches);
            }
        }

        return $matches;
    }

    /**
     * Fetch the migration name to an array data.
     *
     * @return array
     */
    protected function fetchData()
    {
        return explode('_', $this->name);
    }

    /**
     * Get the array data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Determine whether the given type is same with the current schema action or type.
     *
     * @param $type
     * @return bool
     */
    public function is($type)
    {
        return $type == $this->getAction();
    }

    /**
     * Determine whether the current schema action is a adding action.
     *
     * @return bool
     */
    public function isAdd()
    {
        return in_array($this->getAction(), $this->actions['add']);
    }

    /**
     * Determine whether the current schema action is a deleting action.
     *
     * @return bool
     */
    public function isDelete()
    {
        return in_array($this->getAction(), $this->actions['delete']);
    }

    /**
     * Determine whether the current schema action is a creating action.
     *
     * @return bool
     */
    public function isCreate()
    {
        return in_array($this->getAction(), $this->actions['create']);
    }

    /**
     * Determine whether the current schema action is a dropping action.
     *
     * @return bool
     */
    public function isDrop()
    {
        return in_array($this->getAction(), $this->actions['drop']);
    }

} 