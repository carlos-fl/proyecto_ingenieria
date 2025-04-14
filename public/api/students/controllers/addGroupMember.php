<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';
include_once __DIR__ . '/../../../../services/students/types/StudentResponse.php';

session_start();
Request::isWrongRequestMethod('POST');
header("Content-Type: application/json");

if (empty($_SESSION) || !isset($_SESSION["ID_STUDENT"])) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

$request = json_decode(file_get_contents("php://input"));
$studentId = (int) $_SESSION["ID_STUDENT"];
$groupId = isset($request->groupId) ? (int) $request->groupId : null;
$memberIdentifier = trim($request->memberIdentifier ?? '');

if (!$groupId || !$memberIdentifier) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Missing group ID or member identifier")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_ADD_GROUP_MEMBERS(?, ?, ?)";
    $db->callStoredProcedure($query, "iis", [$groupId, $studentId, $memberIdentifier], $mysqli);

    $mysqli->close();
    echo json_encode(new StudentResponse("success"));
} catch (Throwable $err) {
    $mysqli->close();
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}