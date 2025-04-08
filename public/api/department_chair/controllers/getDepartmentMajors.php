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
  echo json_encode([
    "status" => "failure",
    "error" => [
      "errorCode" => 400,
      "errorMessage" => "Unathorized. User not logged in"
    ]
  ]);
  return;
}
$chairmanTeacherNumber = $_SESSION["TEACHER_NUMBER"];
$departmentMajors = DepartmentChairService::getMajors((int) $chairmanTeacherNumber);
if ($departmentMajors["status"] == 'failure') {
  http_response_code($departmentMajors["code"]);
  echo json_encode($departmentMajors);
  return;
}
  // Init in session the ABREVIATION and MAJOR_ID Keys
foreach ($departmentMajors["majors"] as $major){
    $_SESSION["MAJOR"][$major["ABREVIATION"]] = $major["MAJOR_ID"];
}
// Remove Major Id from the dep$departmentMajors
$departmentMajors["majors"] = array_map(function ($elem){
    return array_splice($elem, 1);
}, $departmentMajors["majors"]);
echo json_encode($departmentMajors);




