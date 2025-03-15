<?php

  include_once __DIR__ . '/../../services/auth/types/loginRequest.php';
  include_once __DIR__ . '/../functions/jsonParse.php';

  class Logger {
    public static function loginAuth(string $role) {
      $request = getJsonData();
      $userAuthData = new LoginRequest($request->email, $request->password);

      $userAuthServiceResponse = Auth::login($userAuthData);

      if ($userAuthServiceResponse->status === 'failure') {
        http_response_code($userAuthServiceResponse->error->errorCode);
        echo json_encode($userAuthServiceResponse);
        return;
      }

      $sessionData = json_decode($userAuthServiceResponse->sessionData);
      if (!in_array($role, $sessionData->roles)) {
        http_response_code(403);
        echo json_encode([
          "status" => "failure",
          "data" => null,
          "error" => [
            "errorCode" => 403,
            "errorMessage" => "forbidden"
          ]
        ]);
        return;
      }

      http_response_code(200);
      echo json_encode($userAuthServiceResponse);

      $_SESSION['FIRST_NAME'] = $sessionData->user->FIRST_NAME; 
      $_SESSION['LAST_NAME'] = $sessionData->user->LAST_NAME; 
      $_SESSION['DNI'] = $sessionData->user->DNI; 
      $_SESSION['INST_EMAIL'] = $sessionData->user->INST_EMAIL; 
      $_SESSION['PERSONAL_EMAIL'] = $sessionData->user->PERSONAL_EMAIL; 
      $_SESSION['ROLES'] = $sessionData->roles; 
    }
  }