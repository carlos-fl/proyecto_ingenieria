<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';
include_once __DIR__ . '/../../../../services/students/types/StudentResponse.php';

session_start();
Request::isWrongRequestMethod('POST');

if (empty($_SESSION) || !isset($_SESSION["ID_STUDENT"])) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

$request = getJsonData();
$studentId = (int) $_SESSION["ID_STUDENT"];
$targetId = (int) ($request->targetId ?? 0);
$action = strtoupper(trim($request->action ?? ''));

if (!$targetId || !in_array($action, ['REJECTED', 'BLOCKED'])) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Missing or invalid fields")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_UPDATE_CONTACT_STATUS(?, ?, ?)";
    $db->callStoredProcedure($query, "iis", [$studentId, $targetId, $action], $mysqli);
    $mysqli->close();

    echo json_encode(new StudentResponse("success"));
} catch (Throwable $err) {
    $mysqli->close();
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}

function getJsonData() {
    return json_decode(file_get_contents("php://input"));
}