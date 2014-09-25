<?php namespace Pingpong\Modules\Exceptions;

class ValidationException extends \Exception {

    /**
     * @var mixed
     */
    protected $errors;

    /**
     * @param mixed $errors
     * @param null $message
     */
    public function __construct($errors, $message = null)
    {
        parent::__construct($message);

        $this->errors = $errors;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

}