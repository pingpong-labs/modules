<?php namespace Pingpong\Modules\Schema;

use Pingpong\Modules\Exceptions\InvalidMigrationName;

/**
 * Class Parser
 * @package Pingpong\Modules\Schema
 */
class Parser {

    /**
     * @var
     */
    protected $name;

    /**
     * @var array
     */
    protected $data = [];

    /**
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
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->data = $this->fetchData();
    }

    /**
     * @return mixed
     */
    public function getOriginalName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return head($this->data);
    }

    /**
     * @return mixed|null
     * @throws InvalidMigrationName
     */
    public function getTableName()
    {
        $table = null;

        if ($this->isCreate())
        {
            $table =  array_get($this->data, 1);
        }
        elseif ($this->isDrop())
        {
            $table =  array_get($this->data, 1);
        }
        elseif ($this->isAdd())
        {
            $table =  array_get($this->getMatchesWithAddSchema(), 2);
        }
        elseif ($this->isDelete())
        {
            $table =  array_get($this->getMatchesWithDeleteSchema(), 2);
        }

        if( ! is_null($table)) return $table;

        throw new InvalidMigrationName;
    }

    public function getAlterColumn()
    {
        $table = null;

        if ($this->isAdd())
        {
            $table =  array_get($this->getMatchesWithAddSchema(), 1);
        }
        elseif ($this->isDelete())
        {
            $table =  array_get($this->getMatchesWithDeleteSchema(), 1);
        }

        if( ! is_null($table)) return $table;

        throw new InvalidMigrationName;

    }

    /**
     * @return array
     */
    public function getMatchesWithAddSchema()
    {
        $matches = [];

        foreach($this->actions['add'] as $action)
        {
            if($this->is($action))
            {
                preg_match("/{$action}_(.*)_to_(.*)_table/", $this->name, $matches);
            }
        }

        return $matches;
    }

    /**
     * @return array
     */
    public function getMatchesWithDeleteSchema()
    {
        $matches = [];

        foreach($this->actions['delete'] as $action)
        {
            if($this->is($action))
            {
                preg_match("/{$action}_(.*)_from_(.*)_table/", $this->name, $matches);
            }
        }

        return $matches;
    }

    /**
     * @return array
     */
    protected function fetchData()
    {
        return explode('_', $this->name);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $type
     * @return bool
     */
    public function is($type)
    {
        return $type == $this->getAction();
    }

    /**
     * @return bool
     */
    public function isAdd()
    {
        return in_array($this->getAction(), $this->actions['add']);
    }

    /**
     * @return bool
     */
    public function isDelete()
    {
        return in_array($this->getAction(), $this->actions['delete']);
    }

    /**
     * @return bool
     */
    public function isCreate()
    {
        return in_array($this->getAction(), $this->actions['create']);
    }

    /**
     * @return bool
     */
    public function isDrop()
    {
        return in_array($this->getAction(), $this->actions['drop']);
    }

} 