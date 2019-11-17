<?php

class ApiException extends Exception {

    protected $response_code;
    protected $error_code;
    protected $msg;

    public function __construct($response_code = 500, $error_code = 'G0001', $message = "", Exception $previous = null) {
        $this->response_code = $response_code;
        $this->error_code = $error_code;
        $this->msg = $message;
        parent::__construct("", intval($error_code), $previous);
    }

    final public function getResponseCode() {
        return $this->response_code;
    }

    final public function getErrorCode() {
        return $this->error_code;
    }

    final public function getErrorMessage() {
        return $this->msg;
    }

}