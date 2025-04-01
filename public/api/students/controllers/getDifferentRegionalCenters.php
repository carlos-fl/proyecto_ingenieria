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

$response = StudentService::getDifferentRegionalCenters($studentId);
if ($response["status"] === "failure"){
    http_response_code($response["code"]);
}
else{
    // Init in session the ABREVIATION and CENTER_ID Keys
    foreach ($response["centers"] as $center){
        $_SESSION["CENTER"][$center["ABREVIATION"]] = $center["CENTER_ID"];
    }
    // Remove CENTER_ID from the response
    $response["centers"] = array_map(function ($elem){
        return array_splice($elem, 1);
    }, $response["centers"]);
}
echo json_encode($response);
