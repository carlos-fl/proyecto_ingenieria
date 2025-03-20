<?php

  class LogApplicant {
    private int $applicantCode;

    public function __construct(int $applicantCode) {
      $this->applicantCode = $applicantCode;
    }

    public function getApplicantCode(): int {
      return $this->applicantCode;
    }
  }