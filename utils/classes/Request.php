<?php

  class Request {
    public static function isWrongRequestMethod(string $HTTPMethod): void {
      if ($_SERVER['REQUEST_METHOD'] !== $HTTPMethod) {
        http_response_code(400);
        echo json_encode([
          "status" => "failure",
          "error" => [
            "errorCode" => 400,
            "errorMessage" => "Invalid request method"
          ]
        ]);
      }
    }
  }