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
$currentCreatorId = (int) $_SESSION["ID_STUDENT"];

$groupId = isset($request->groupId) ? (int) $request->groupId : null;
$newOwnerId = isset($request->newOwnerId) ? (int) $request->newOwnerId : null;

if (!$groupId || !$newOwnerId) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Missing required parameters")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_TRANSFER_GROUP_OWNERSHIP(?, ?, ?)";
    $db->callStoredProcedure($query, "iii", [$groupId, $currentCreatorId, $newOwnerId], $mysqli);

    $mysqli->close();

    echo json_encode(new StudentResponse("success"));
} catch (Throwable $err) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}