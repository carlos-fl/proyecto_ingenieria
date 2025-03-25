<?php

  include_once __DIR__ . '/../../../utils/types/postResponse.php';

  class CSVProcessStatus {
    public ?ErrorResponse $error;
    public string $status;

    public function __construct(string $status, ?ErrorResponse $error = null) {
      $this->status = $status;
      $this->error = $error;
    }
  }