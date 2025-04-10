<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../services/department_chair/DepartmentChairService.php';

session_start();

header('Content-Type: application/json');

Request::isWrongRequestMethod('GET');
if (!Request::haveRol('DEPARTMENT_CHAIR')) {
  return;
}
if (!isset($_SESSION["TEACHER_NUMBER"])){
    http_response_code(401);
    echo ["status" => "failure", "message" => "Unathorized. Not logged in"];
    return;
}
$buildingId = $_GET["building"];
$classrooms = DepartmentChairService::getDepartmentBuildingClassrooms($_SESSION["TEACHER_NUMBER"], $buildingId);
if ($classrooms["status"] == 'failure') {
  http_response_code($classrooms["code"]);
  echo json_encode($classrooms);
  return;
}
echo json_encode($classrooms);




