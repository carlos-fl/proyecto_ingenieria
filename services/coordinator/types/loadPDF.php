<?php

  include_once __DIR__ . '/../../../utils/types/postResponse.php';

  class LoadPDF {
    public string $status;
    public string $data;
    public ?ErrorResponse $error;

    public function __construct(string $status, string $data = '', ?ErrorResponse $error = null) {
      $this->status = $status;
      $this->data = $data; 
      $this->error = $error;
    }
  }