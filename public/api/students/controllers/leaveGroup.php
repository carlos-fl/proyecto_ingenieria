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

if (!$groupId) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Missing groupId")));
    return;
}

try {
    $db = Database::getDatabaseInstace();
    $mysqli = $db->getConnection();

    $query = "CALL SP_LEAVE_GROUP(?, ?)";
    $db->callStoredProcedure($query, "ii", [$studentId, $groupId], $mysqli);
    $mysqli->close();

    echo json_encode(new StudentResponse("success"));
} catch (Throwable $err) {
    http_response_code(500);
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}