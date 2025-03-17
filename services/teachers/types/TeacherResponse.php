<?php

  include_once __DIR__ . '/../../../utils/types/postResponse.php';

  class TeacherResponse {
    public string $status;
    public ?TeacherData $data;
    public array $roles;
    public ?ErrorResponse $error;

    public function __construct(string $status, ?TeacherData $data = null, array $roles = [], ?ErrorResponse $error = null) {
      $this->status = $status;
      $this->data = $data;
      $this->roles = $roles;
      $this->error = $error;
    }

    public function getStatus(): string {
      return $this->status;
    }

    public function getError(): ErrorResponse {
      return $this->error;
    }
  }