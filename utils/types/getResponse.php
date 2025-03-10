<?php

  class GetResponse {
    public string $status;
    public array $data;

    public function __construct(string $status, array $data) {
      $this->status = $status;
      $this->data = $data;
    }
  }