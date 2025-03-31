<?php
include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../services/students/services/Students.php';
include_once __DIR__ . '/../../../../services/students/types/MajorChangeStudentRequest.php';
include_once __DIR__ . '/../../../../services/students/types/StudentRequestType.php';

// Realizar una nueva solicitud de cambio de carrera


Request::isWrongRequestMethod('POST');


session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["ID_STUDENT"])){
    http_response_code(401);
    echo json_encode(["status" => "failure", "message" => "User not logged in"]);
    return;
}
$studentId = $_SESSION["ID_STUDENT"];
// Tipo de solicitud que realiza el estudiante
$requestType = $_POST["requestType"];

if ($requestType === StudentRequestType::MAJORCHANGE){
    $majorId = $_SESSION["MAJOR"][$_POST["major"]];
    $content = $_POST["content"];
    $studentRequest = new MajorChangeStudentRequest($majorId, $content);
}
else {
    http_response_code(400);
    echo json_encode(["status" => "failure", "message" => "No se soporta el tipo de solicitud enviada"]);
    return;
}

$response = StudentService::newStudentRequest($studentId, $studentRequest);
if ($response["status"] === "failure"){
    http_response_code($response["code"]);
}
echo json_encode($response);
