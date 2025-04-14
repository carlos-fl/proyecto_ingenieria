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

$studentId = (int) $_SESSION["ID_STUDENT"];
$data = json_decode(file_get_contents("php://input"));
$groupName = trim($data->groupName ?? '');

if (empty($groupName)) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Group name is required")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_CREATE_GROUP(?, ?)";
    $result = $db->callStoredProcedure($query, "si", [$groupName, $studentId], $mysqli);
    $row = $result->fetch_assoc();

    $groupId = (int) $row["groupId"];

    $mysqli->close();

    echo json_encode([
        "status" => "success",
        "data" => ["groupId" => $groupId],
        "error" => null
    ]);
} catch (Throwable $err) {
    $mysqli->close();
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}