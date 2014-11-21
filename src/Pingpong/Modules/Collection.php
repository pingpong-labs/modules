<?php namespace Pingpong\Modules;

use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection {

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

} 