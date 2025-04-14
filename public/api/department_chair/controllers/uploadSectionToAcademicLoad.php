<?php

// Retorna los edificios en los que el departmento tiene aulas (Dependiendo del centro regional al que pertenece el jefe)

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../services/department_chair/DepartmentChairService.php';

session_start();

header('Content-Type: application/json');

Request::isWrongRequestMethod('POST');
if (!Request::haveRol('DEPARTMENT_CHAIR')) {
  return;
}
if (!isset($_SESSION["TEACHER_NUMBER"])){
    http_response_code(401);
    echo ["status" => "failure", "message" => "Unathorized. Not logged in"];
    return;
}

$departmentChairNumber = $_SESSION["TEACHER_NUMBER"];

$classCode = $_POST["class-code"];
$teacher =$_POST["teacher-number"];
$classDays =$_POST["class-days"];
$startTime =$_POST["start-time"];
$endTime =$_POST["end-time"];
$building = $_POST["building"];
$classroom = $_POST["classroom"];
$quota = $_POST["quota"];

if ($quota > 40){
    http_response_code(402);
    echo json_encode(["status" => "failure", "message" => "Cantidad de Cupos máxima excedida"]);
    return;
}

$classId = $_SESSION["CLASS"][$classCode];
$newSection = DepartmentChairService::newSection($departmentChairNumber, $classId, $teacher, json_encode($classDays), $startTime, $endTime, $building, $classroom, $quota);
if ($newSection["status"] == 'failure') {
  http_response_code($newSection["code"]);
  echo json_encode($newSection);
  return;
}
echo json_encode($newSection);