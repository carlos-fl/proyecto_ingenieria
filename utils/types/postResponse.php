<?php

  class ErrorResponse {
    public int $errorCode;
    public string $errorMessage;
  }

  class PostResponse {
   public string $status; 
   public ?array $data = null;
   public ?string $sessionData = null;
   public ?ErrorResponse $error = null; 
  }