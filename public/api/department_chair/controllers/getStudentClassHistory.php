<?php 

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../services/department_chair/DepartmentChairService.php';
  

  session_start();

  header('Content-Type: application/json');

  if (!Request::haveRol('DEPARTMENT_CHAIR')) {
    return;
  }
  
  Request::isWrongRequestMethod('GET');

  $account = $_GET['accountNumber'];

  if (!isset($account)) {
    http_response_code(400);
    echo json_encode([
      "status" => "failure",
      "data" => [],
      "error" => [
        "errorCode" => 400,
        "errorMessage" => "Account number required"
      ]
    ]);
    return;
  }

  $classHistory = DepartmentChairService::getStudentClassHistory((int) $account);
  if ($classHistory->status == 'failure') {
    http_response_code($classHistory->error->errorCode);
    echo json_encode($classHistory);
    return;
  }

  http_response_code(200);
  echo json_encode($classHistory);




