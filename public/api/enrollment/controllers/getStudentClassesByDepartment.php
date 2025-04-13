<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';
include_once __DIR__ . '/../../../../services/students/types/StudentResponse.php';

session_start();
Request::isWrongRequestMethod('GET');

if (empty($_SESSION) || !isset($_SESSION["ID_STUDENT"])) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

$studentId = (int) $_SESSION["ID_STUDENT"];
$majorId = isset($_GET["majorId"]) ? (int) $_GET["majorId"] : null;
$departmentId = isset($_GET["departmentId"]) ? (int) $_GET["departmentId"] : null;

if (!$majorId || !$departmentId) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Missing major or department")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_GET_AVAILABLE_CLASSES_BY_DEPARTMENT(?, ?)";
    $result = $db->callStoredProcedure($query, "ii", [$studentId, $majorId], $mysqli);

    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }

    $mysqli->close();
    echo json_encode(new StudentResponse("success", $classes));
} catch (Throwable $err) {
    $mysqli->close();
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}