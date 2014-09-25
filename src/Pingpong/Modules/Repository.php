<?php namespace Pingpong\Modules;

abstract class Repository {

    /**
     * The model name.
     *
     * @var string
     */
    protected $model;

    /**
     * The constructor.
     */
    public function __construct()
    {
        $this->model = new $this->model;
    }

    /**
     * Get all data.
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get paginated data.
     *
     * @param int $perPage
     * @return mixed
     */
    public function getAllPaginated($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

} 