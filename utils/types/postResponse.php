<?php

  class ErrorResponse {
    public int $errorCode;
    public string $errorMessage;

    public function __construct(int $errorCode, string $errorMessage) {
      $this->errorCode = $errorCode;
      $this->errorMessage = $errorMessage; 
    }
  }

  class PostResponse {
   public string $status; 
   public ?array $data = null;
   public ?string $sessionData = null;
   public ?ErrorResponse $error = null; 
  }