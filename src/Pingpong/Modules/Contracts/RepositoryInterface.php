<?php namespace Pingpong\Modules\Contracts;

interface RepositoryInterface {

    /**
     * Get all modules.
     *
     * @return mixed
     */
    public function all();

    /**
     * Get list of enabled modules.
     *
     * @return mixed
     */
    public function enabled();

    /**
     * Get list of disabled modules.
     *
     * @return mixed
     */
    public function disabled();

    /**
     * Get count from all modules.
     *
     * @return int
     */
    public function count();

    /**
     * Get all ordered modules.
     *
     * @return mixed
     */
    public function getOrdered();

    public function getByStatus($status);

    public function find($name);

    public function findOrFail($name);

}