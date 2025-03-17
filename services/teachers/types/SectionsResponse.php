<?php

  class SectionResponse {
    public string $status;
    public ?SectionData $data;
    public ?ErrorResponse $error;

    public function __construct(string $status, ?SectionData $data = null, ?ErrorResponse $error = null) {
      $this->status = $status;
      $this->data = $data;
      $this->error = $error;
    }
  }