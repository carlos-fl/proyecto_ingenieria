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
    public static function returnPostResponse(?int $code = null, string $status, ?string $message = null, bool $isBadResponse): PostResponse {
      //TODO: validate code is a number in range of http code
      //TODO: validate status and message with regex
      if (!$isBadResponse) {
        $response = new PostResponse();
        $response->status = $status;
        return $response;
      }

      $response = new PostResponse();
      $errorResponse = new ErrorResponse();
      $errorResponse->errorCode = $code;
      $errorResponse->errorMessage = $message;
      $response->status = $status;
      $response->error = $errorResponse;
      return $response;
    }
  }