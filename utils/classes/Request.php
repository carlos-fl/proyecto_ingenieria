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
        return;
      }
    }

    public static function haveRol(string $role): bool {
      if (empty($_SESSION) || !in_array($role, $_SESSION['ROLES'])) {
        http_response_code(403);
        echo json_encode([
          "status" => "failure",
          "data" => [],
          "error" => [
            "errorCode" => 403,
            "errorMessage" => "Forbidden"
          ]
        ]);
        return false;
      }
      return true;
    }
  }