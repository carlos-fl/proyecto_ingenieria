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
$classes = DepartmentChairService::getMajorClasses($majorId);
if ($classes["status"] == 'failure') {
  http_response_code($classes["code"]);
  echo json_encode($classes);
  return;
}

 // Init in session the ABREVIATION and CLASS_ID Keys
foreach ($classes["classes"] as $class){
    $_SESSION["CLASS"][$class["CLASS_CODE"]] = $class["ID_CLASS"];
}

// Remove Class id from the $classes
$classes["classes"] = array_map(function ($elem){
    return array_splice($elem, 1);
}, $classes["classes"]);
echo json_encode($classes);




