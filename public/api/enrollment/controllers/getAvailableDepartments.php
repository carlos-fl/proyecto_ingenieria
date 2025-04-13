<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';
include_once __DIR__ . '/../../../../services/students/types/StudentResponse.php';

session_start();
Request::isWrongRequestMethod('GET');
header("Content-Type: application/json");

if (!isset($_SESSION["ID_STUDENT"])) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

$studentId = (int) $_SESSION["ID_STUDENT"];
$majorId = isset($_GET["majorId"]) ? (int) $_GET["majorId"] : null;

if (!$majorId) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Missing or invalid majorId")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {

    $query = "CALL SP_GET_AVAILABLE_DEPARTMENTS(?, ?)";

    $result = $db->callStoredProcedure($query, "ii", [$studentId, $majorId], $mysqli);

    $departments = [];
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }

    $mysqli->close();
    echo json_encode(new StudentResponse("success", $departments));
} catch (Throwable $err) {
    $mysqli->close();
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}