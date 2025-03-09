<?php

  include_once __DIR__ . '/../types/postResponse.php';

  class Response {
    /**
     * @param int $code
     * http status code
     * @param string $status
     * either success or failure
     * @param string $message
     * message that match the http code status
     * @param bool $isBadResponse
     * @return PostResponse $response
     */
    public static function returnPostResponse(bool $isBadResponse, ?string $status, ?int $code = null, ?string $message = null, ?array $data = null, ?string $sessionData = null): PostResponse {
      //TODO: validate code is a number in range of http code
      //TODO: validate status and message with regex

      $response = new PostResponse();
      if (!$isBadResponse) {
        $response->status = $status;
        $response->data = $data;
        $response->sessionData = $sessionData;
        $response->error = null;
        return $response;
      }

      $errorResponse = new ErrorResponse();
      $errorResponse->errorCode = $code;
      $errorResponse->errorMessage = $message;
      $response->status = $status;
      $response->error = $errorResponse;
      return $response;
    }
  }