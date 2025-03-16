<?php

  include_once __DIR__ . '/../../../utils/types/postResponse.php';

  class TeacherResponse {
    private string $status;
    private TeacherData $data;
    private array $roles;
    private ErrorResponse $error;

    public function __construct(string $status, ?TeacherData $data = null, array $roles = [], ?ErrorResponse $error = null) {
      $this->status = $status;
      $this->data = $data;
      $this->roles = $roles;
      $this->error = $error;
    }
  }