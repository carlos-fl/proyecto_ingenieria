<?php
include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../services/students/services/Students.php';

Request::isWrongRequestMethod('GET');


session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["ID_STUDENT"])){
    http_response_code(401);
    echo json_encode(["status" => "failure", "message" => "User not logged in"]);
    return;
}

$studentId = $_SESSION["ID_STUDENT"];

$response = StudentService::getDifferentMajors($studentId);
if ($response["status"] === "failure"){
    http_response_code($response["code"]);
}
else{
    // Init in session the ABREVIATION and MAJOR_ID Keys
    foreach ($response["majors"] as $major){
        $_SESSION["MAJOR"][$major["ABREVIATION"]] = $major["MAJOR_ID"];
    }
    // Remove Major Id from the response
    $response["majors"] = array_map(function ($elem){
        return array_splice($elem, 1);
    }, $response["majors"]);
}
echo json_encode($response);
