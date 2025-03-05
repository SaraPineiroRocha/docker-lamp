<?php

class DatabaseException extends Exception {
    private $method;
    private $sql;

    public function __construct($message, $method, $sql, $code = 0, Exception $previous = null) {
        $this->method = $method;
        $this->sql = $sql;
        parent::__construct($message, $code, $previous);
    }

    public function getMethod() {
        return $this->method;
    }

    public function getSql() {
        return $this->sql;
    }
}
?>
