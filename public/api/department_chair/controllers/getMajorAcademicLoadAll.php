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
$majorAbreviation = $_GET["major"];
$majorId = $_SESSION["MAJOR"][$majorAbreviation];
$chairmanTeacherNumber = $_SESSION["TEACHER_NUMBER"];
$academicLoad = DepartmentChairService::getAcademicLoadAll($majorId, $chairmanTeacherNumber);
if ($academicLoad["status"] == 'failure') {
  http_response_code($academicLoad["code"]);
  echo json_encode($academicLoad);
  return;
}
echo json_encode($academicLoad);




