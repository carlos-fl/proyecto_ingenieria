<?php

// Retorna los edificios en los que el departmento tiene aulas (Dependiendo del centro regional al que pertenece el jefe)

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
$buildings = DepartmentChairService::getDepartmentBuildings($_SESSION["TEACHER_NUMBER"]);
if ($buildings["status"] == 'failure') {
  http_response_code($buildings["code"]);
  echo json_encode($buildings);
  return;
}
echo json_encode($buildings);




