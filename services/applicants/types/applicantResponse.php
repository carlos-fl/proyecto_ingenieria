<?php

  include_once __DIR__ . '/applicantFormData.php';
  include_once __DIR__ . '/../../../utils/types/postResponse.php';

  class ApplicantResponse {
    public string $status;
    public ?array $applicant;
    public ?ErrorResponse $error;

    public function __construct(string $status, ?array $applicant = null, ?ErrorResponse $error = null) {
      $this->status = $status;
      $this->applicant = $applicant;
      $this->error = $error; 
    }
  }