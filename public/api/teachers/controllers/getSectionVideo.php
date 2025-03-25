<?php
include_once __DIR__ . '/../../../../config/env/Environment.php';
include_once __DIR__ . '/../../../../utils/classes/Encrypt.php';
include_once __DIR__ . '/../../../../services/teachers/services/Teachers.php';
include_once __DIR__ . '/../../../../utils/classes/Request.php';

session_start();
header("Content-Type", "application/json");
Request::isWrongRequestMethod("GET");

if (empty($_SESSION)) {
    setUnauthorizedResponse();
    return;
}
$sectionId = $_GET["section"];
Environment::read();
$env = Environment::getVariables();
$encryption = new Encryption($env["CYPHER_ALGO"], $env["CYPHER_KEY"]);

if (!isset($_SESSION[$sectionId])) {
    echo json_encode(new DataResponse("failure", error: new ErrorResponse(400, "Invalid Section ID")));
    return;
}

$encryptionIv = $_SESSION[$sectionId];
$sectionId = $encryption->decrypt($sectionId, $encryptionIv);

$response = TeacherService::getSectionCurrentVideo((int) $sectionId);
if (!$response){
    http_response_code(500);
    echo json_encode(["status" => "failure", "message" => "No connection with database"]);
    return;
}
$response = ["status" => "success", "videoUrl" => $response["SECTION_VIDEO_URL"] ?? ""];
http_response_code(200);
echo json_encode($response);