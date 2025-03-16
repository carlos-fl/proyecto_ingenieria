<?php

  include_once __DIR__ . '/../../../utils/types/postResponse.php';

  class DataResponse {
    private string $status;
    private array $data;
    private ErrorResponse $error;


    public function __construct(string $status, array $data = [], ?ErrorResponse $error = null) {
      $this->status = $status;
      $this->data = $data;
      $this->error = $error;
    }
  }