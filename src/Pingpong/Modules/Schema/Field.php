<?php namespace Pingpong\Modules\Schema;

class Field {

    /**
     * @var null
     */
    protected $fields;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param null $fields
     */
    public function __construct($fields = null)
    {
        $this->fields = $fields;
        $this->data = $fields ? $this->fetchData() : [];
    }

    /**
     * @return array
     */
    public function fetchData()
    {
        return preg_split('/\s?,\s/', $this->fields);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getParsedFields()
    {
        $parsed = [];

        foreach($this->data as $data)
        {
            $parsed[] = explode(':', $data);
        }

        return $parsed;
    }

    /**
     * @return string
     */
    public function getSchemaCreate()
    {
        $content = '';

        $fields = $this->getParsedFields();

        foreach($fields as $key => $field)
        {
            $typeData = array_get($field, 1);

            $fieldName = array_get($field, 0);

            $others = array_slice($field, 2);

            $tab = $key > 0 ? '            ' : '';

            if(preg_match('/\((.*)\)/',  $typeData, $matches))
            {
                $length = $matches[1];

                $typeData = str_replace($matches[0], '', $typeData);

                $content .= $tab . '$table->' . $typeData . "('{$fieldName}', {$length})";
            }
            else{
                $content .= $tab . '$table->' . $typeData . "('{$fieldName}')";
            }

            if(count($field) > 2)
            {
                $content = $this->createOthersField($others, $content);
            }

            $content .= (($key + 1) == count($fields)) ? ';' : ';' . PHP_EOL;
        }

        return $content;
    }

    /**
     * @param $others
     * @param $content
     * @return string
     */
    protected function createOthersField($others, $content)
    {
        foreach ($others as $other)
        {
            if ( ! str_contains($other, ['(', ')']))
            {
                $content .= '->' . $other . '()';
            }
            else
            {
                $content .= '->' . $other;
            }
        }
        return $content;
    }

    /**
     * @return string
     */
    public function getSchemaDropColumn()
    {
        $content = '';

        foreach($this->getParsedFields() as $field)
        {
            $column = array_get($field, 0);

            $content .= '$table->dropColumn' . "('{$column}');";
        }

        return $content;
    }
} 