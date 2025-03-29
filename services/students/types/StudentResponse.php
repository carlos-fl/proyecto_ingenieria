<?php

include_once __DIR__ . '/../../../utils/types/postResponse.php';

class StudentResponse {
    public string $status;
    public ?StudentData $data;
    public ?ErrorResponse $error;

    public function __construct(string $status, ?StudentData $data = null, ?ErrorResponse $error = null) {
        $this->status = $status;
        $this->data = $data;
        $this->error = $error;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getError(): ErrorResponse {
        return $this->error;
    }
}