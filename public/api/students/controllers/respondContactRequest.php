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
$requesterId = $request->requesterId ?? null;
$action = strtoupper(trim($request->action ?? ''));

$validActions = ['ACCEPT' => 'ADDED', 'REJECT' => 'REJECTED', 'BLOCK' => 'BLOCKED'];

if (!$requesterId || !array_key_exists($action, $validActions)) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Invalid requester or action")));
    return;
}

$newStatus = $validActions[$action];

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_UPDATE_CONTACT_STATUS(?, ?, ?)";
    $db->callStoredProcedure($query, "iis", [$studentId, $requesterId, $newStatus], $mysqli);
    $mysqli->close();

    echo json_encode(new StudentResponse("success"));
} catch (Throwable $err) {
    $mysqli->close();
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}