<?php

  include_once __DIR__ . '/../../../utils/types/postResponse.php';

  class EmailStatus {
    public bool $success;
    public ?ErrorResponse $error;

    public function __construct(bool $success, ?ErrorResponse $err = null) {
      $this->success = $success;
      $this->error = $err; 
    }
  }