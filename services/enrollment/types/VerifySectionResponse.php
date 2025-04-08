<?php

  include_once __DIR__ . '/../../../utils/types/postResponse.php';

  class VerifySectionResponse {
    public string $status;
    public bool $isValid;
    public ?ErrorResponse $error;

    public function __construct(string $status, bool $isValid, ?ErrorResponse $error = null) {
      $this->status = $status;
      $this->isValid = $isValid;
      $this->error = $error; 
    }
  }