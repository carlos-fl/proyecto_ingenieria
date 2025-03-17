<?php

  include_once __DIR__ . '/../../../utils/types/postResponse.php';

  class DataResponse {
    public string $status;
    public array $data;
    public ?ErrorResponse $error;


    public function __construct(string $status, array $data = [], ?ErrorResponse $error = null) {
      $this->status = $status;
      $this->data = $data;
      $this->error = $error;
    }
  }