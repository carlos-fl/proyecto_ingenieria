<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';
include_once __DIR__ . '/../../../../services/students/types/StudentResponse.php';

session_start();
Request::isWrongRequestMethod('GET');
header("Content-Type: application/json");

if (empty($_SESSION) || !isset($_SESSION["ID_STUDENT"])) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

$classId = isset($_GET["classId"]) ? (int)$_GET["classId"] : 0;
if (!$classId) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Missing or invalid classId")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_GET_SECTIONS_BY_CLASS(?)";
    $result = $db->callStoredProcedure($query, "i", [$classId], $mysqli);

    $sections = [];
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }

    $mysqli->close();

    echo json_encode(new StudentResponse("success", $sections));
} catch (Throwable $err) {
    $mysqli->close();
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}