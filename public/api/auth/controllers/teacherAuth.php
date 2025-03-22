<?php

  session_start();
  header('Content-Type: application/json');

  include_once __DIR__ .'/../../../../services/auth/services/auth.service.php';
  include_once __DIR__ . '/../../../../utils/classes/Logger.php';
  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/teachers/services/Teachers.php';

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
  if (!in_array("TEACHERS", $sessionData->roles)) {
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
  
  // set TEACHERNUMBER in session
  $userId = $sessionData->user->USER_ID;
  $teacherNumber = TeacherService::getTeacherNumber($userId);
  $employeeNumber = TeacherService::getEmployeeNumber($userId);

  http_response_code(200);
  echo json_encode([
    "status" => "success",
    "data" => [],
    "sessionData" => [
      "user" => json_encode($sessionData->user),
      "roles" => json_encode($sessionData->roles),
      "teacherNumber" => $teacherNumber,
      "employeeNumber" => $employeeNumber["EMPLOYEE_NUMBER"]
    ],
    "error" => null
  ]);

  $_SESSION['FIRST_NAME'] = $sessionData->user->FIRST_NAME; 
  $_SESSION['LAST_NAME'] = $sessionData->user->LAST_NAME; 
  $_SESSION['DNI'] = $sessionData->user->DNI; 
  $_SESSION["USER_ID"] = $sessionData->user->USER_ID;
  $_SESSION['INST_EMAIL'] = $sessionData->user->INST_EMAIL; 
  $_SESSION['PERSONAL_EMAIL'] = $sessionData->user->PERSONAL_EMAIL; 
  $_SESSION['ROLES'] = $sessionData->roles;
  $_SESSION["TEACHER_NUMBER"] = $teacherNumber;

