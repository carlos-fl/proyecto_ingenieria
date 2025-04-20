<?php

session_start();

include_once __DIR__ . '/../../../../services/auth/services/auth.service.php';
include_once __DIR__ . '/../../../../utils/classes/Logger.php';
include_once __DIR__ . '/../../../../utils/classes/Request.php';

header("Content-Type: application/json");

Request::isWrongRequestMethod('POST');
$request = getJsonData();
$userAuthData = new LoginRequest($request->email, $request->password);

$userAuthServiceResponse = Auth::login($userAuthData);

if ($userAuthServiceResponse->status === 'failure') {
  http_response_code($userAuthServiceResponse->error->errorCode);
  echo json_encode($userAuthServiceResponse);
  return;
}

$sessionData = json_decode($userAuthServiceResponse->sessionData);
$ROLES = ["ADMINISTRATOR"];
if (!in_array($ROLES[0], $sessionData->roles) && !in_array($ROLES[0], $sessionData->roles)) {
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
echo json_encode([
  "status" => "success",
  "data" => [],
  "sessionData" => [
    "user" => $sessionData->user,
    "roles" => $sessionData->roles
  ],
  "error" => null
]);

$_SESSION['USER_ID'] = $sessionData->user->USER_ID;
$_SESSION['FIRST_NAME'] = $sessionData->user->FIRST_NAME;
$_SESSION['LAST_NAME'] = $sessionData->user->LAST_NAME;
$_SESSION['DNI'] = $sessionData->user->DNI;
$_SESSION['INST_EMAIL'] = $sessionData->user->INST_EMAIL;
$_SESSION['PERSONAL_EMAIL'] = $sessionData->user->PERSONAL_EMAIL;
$_SESSION['ROLES'] = $sessionData->roles;
$_SESSION['PHOTO'] = $sessionData->user->PHOTO;
$_SESSION['PHONE'] = $sessionData->user->PHONE_NUMBER;
$_SESSION['USER'] = $sessionData->user;

