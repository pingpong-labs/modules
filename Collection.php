<?php namespace Pingpong\Modules;

use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection {

    /**
     * Get items collections.
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value)
        {
            if ($value instanceof Module) return $value->json()->getAttributes();

            return $value instanceof ArrayableInterface ? $value->toArray() : $value;

        }, $this->items);
    }

} 