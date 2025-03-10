<?php

  class ErrorResponse {
    public int $errorCode;
    public string $errorMessage;
  }

  class PostResponse {
   public string $status; 
   public ErrorResponse $error; 
  }