<?php
include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../services/students/services/Students.php';
include_once __DIR__ . '/../../../../services/students/types/StudentRequestType.php';

// Traer las solicitudes de cambio de carrera de un estudiante

Request::isWrongRequestMethod('GET');


session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["ID_STUDENT"])){
    http_response_code(401);
    echo json_encode(["status" => "failure", "message" => "User not logged in"]);
    return;
}

$studentId = $_SESSION["ID_STUDENT"];

$response = StudentService::getClassCancelRequests($studentId);
if ($response["status"] === "failure"){
    http_response_code($response["code"]);
}
echo json_encode($response);
