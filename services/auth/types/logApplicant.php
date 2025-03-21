<?php

  class LogApplicant {
    private string $applicantCode;

    public function __construct(string $applicantCode) {
      $this->applicantCode = $applicantCode;
    }

    public function getApplicantCode(): string {
      return $this->applicantCode;
    }
  }