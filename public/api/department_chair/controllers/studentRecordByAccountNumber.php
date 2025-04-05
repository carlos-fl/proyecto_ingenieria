<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../services/department_chair/DepartmentChairService.php';

session_start();

header('Content-Type: application/json');

if (!Request::haveRol('DEPARTMENT_CHAIR')) {
  return;
}

Request::isWrongRequestMethod('GET');

$account = $_GET['account'];

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

$studentRecordHeader = DepartmentChairService::getStudentRecordHeader((int) $account);
if ($studentRecordHeader["status"] == 'failure') {
  http_response_code($studentRecordHeader["code"]);
  echo json_encode($studentRecordHeader);
  return;
}
echo json_encode($coordinatorServiceResponse);




