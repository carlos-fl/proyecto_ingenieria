<?php

  class LogApplicant {
    private int $applicantCode;
    private string $email;

    public function __construct(int $applicantCode, string $email) {
      $this->applicantCode = $applicantCode;
      $this->email = $email;
    }

    public function getApplicantCode(): int {
      return $this->applicantCode;
    }

    public function getApplicantEmail(): string {
      return $this->email;
    }
  }