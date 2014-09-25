<?php namespace Pingpong\Modules\Validator;

use Illuminate\Routing\Controller;
use Illuminate\Validation\Factory as Validator;
use Pingpong\Modules\Exceptions\ValidationException;

abstract class ControllerValidator extends Controller {

    /**
     * The validation rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * The validation messages.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * The validator instance.
     *
     * @param Validator $validator
     */
    protected $validator;

    /**
     * @var \Illuminate\Validation\Validator $validation
     */
    protected $validation;

    /**
     * The constructor.
     *
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validate the given data.
     *
     * @param  array $data
     * @throws ValidationException
     * @return bool
     */
    public function validate(array $data)
    {
        $this->validation = $this->validator->make($data, $this->getRules(), $this->getMessages());

        if ($this->validation->fails())
        {
            throw new ValidationException($this->getErrors(), "Validation failed");
        }

        return true;
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Get the validation errors.
     *
     * @return mixed
     */
    public function getErrors()
    {
        return $this->validation->messages();
    }

}