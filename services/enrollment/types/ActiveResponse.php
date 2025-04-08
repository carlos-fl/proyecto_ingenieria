<?php

  include_once __DIR__ . '/../../../utils/types/postResponse.php';

  class ActiveResponse {
    public string $status;
    public bool $isActive;
    public ?ErrorResponse $error;

    public function __construct(string $status, bool $isActive, ?ErrorResponse $err = null) {
      $this->status = $status;
      $this->isActive = $isActive;
      $this->error = $err;
    }
  }