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

$typeAbbreviation = isset($_GET["type"]) ? strtoupper(trim($_GET["type"])) : null;

if (!$typeAbbreviation) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Missing type abbreviation")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_GET_ACTIVE_PROCESS_BY_PAC_AND_TYPE(?)";
    $result = $db->callStoredProcedure($query, "s", [$typeAbbreviation], $mysqli);

    if ($result->num_rows === 0) {
        $mysqli->close();
        echo json_encode(new StudentResponse("failure", error: new ErrorResponse(404, "No active process found")));
        return;
    }

    $process = $result->fetch_assoc();
    $mysqli->close();

    echo json_encode(new StudentResponse("success", $process));

} catch (Throwable $err) {
    $mysqli->close();
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}