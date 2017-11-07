<?php

namespace Jose\Exception;

class ValidationException extends \Exception {

    protected $errors = [];

    public function __construct($errors, $code, $exception = null) {
        $this->errors = !is_array($errors) ? : array_values($errors);
        parent::__construct('Initial validation failed; see validation errors', $code, $exception);
    }

    public function getValidationErrors() {
        return $this->errors;
    }
}
