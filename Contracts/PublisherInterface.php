<?php

namespace Pingpong\Modules\Contracts;

interface PublisherInterface
{
    /**
     * Publish something.
     *
     * @return mixed
     */
    public function publish();
}
