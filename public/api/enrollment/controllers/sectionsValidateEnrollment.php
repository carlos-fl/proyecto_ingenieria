<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';
include_once __DIR__ . '/../../../../services/students/types/StudentResponse.php';

session_start();
Request::isWrongRequestMethod("POST");

if (empty($_SESSION) || !isset($_SESSION["ID_STUDENT"])) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

$request = json_decode(file_get_contents("php://input"));
$studentId = $_SESSION["ID_STUDENT"];
$sectionId = $request->sectionId ?? null;

if (!$sectionId || !is_numeric($sectionId)) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Invalid or missing sectionId")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_VALIDATE_SECTION_ELIGIBILITY(?, ?)";
    $db->callStoredProcedure($query, "ii", [$studentId, $sectionId], $mysqli);
    $mysqli->close();

    echo json_encode(new StudentResponse("success"));
} catch (Throwable $err) {
    $mysqli->close();
    http_response_code(400);
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, $err->getMessage())));
}