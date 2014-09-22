<?php namespace Pingpong\Modules\Exceptions;

class ValidationException extends \Exception {

	protected $errors;

	public function __construct($errors, $message = null)
	{
		parent::__construct($message);

		$this->errors = $errors;
	}

	public function getErrors()
	{
		return $this->errors;
	}
	
}