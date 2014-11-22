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

} 