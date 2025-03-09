<?php

  session_start();

  include_once __DIR__ .'/../services/auth.service.php';
  include_once __DIR__ . '/../types/logApplicant.php';
  include_once __DIR__ . '/../../../../utils/classes/Response.php';
  include_once __DIR__ . '/../../../../utils/functions/jsonParse.php';

  header("Content-Type: application/json");

  Request::isWrongRequestMethod('POST'); 

  $request = getJsonData();
  $applicantAuthData = new LogApplicant($request->applicantCode, $request->email);

  $authServiceResponse = Auth::logApplicant($applicantAuthData);

  if ($authServiceResponse->data === null || $authServiceResponse->status === 'failure') {
    http_response_code($authServiceResponse->error->errorCode);
    echo json_encode($authServiceResponse);
    return;
  }

  if (count($authServiceResponse->data) === 0) {
    http_response_code(200);
    echo json_encode($authServiceResponse);
    return;
  }

  http_response_code(200);
  echo json_encode([
    "status" => $authServiceResponse->status,
    "data" => $authServiceResponse->data,
    "error" => null
  ]);

  $sessionData = json_decode($authServiceResponse->sessionData);
  $_SESSION['FIRST_NAME'] = $sessionData['user']['FIRST_NAME']; 
  $_SESSION['LAST_NAME'] = $sessionData['user']['LAST_NAME']; 
  $_SESSION['DNI'] = $sessionData['user']['DNI']; 
  $_SESSION['EMAIL'] = $sessionData['user']['EMAIL']; 