<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';
include_once __DIR__ . '/../../../../services/students/types/StudentResponse.php';

session_start();
Request::isWrongRequestMethod('GET');

if (empty($_SESSION)) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

$studentId = $_SESSION["ID_STUDENT"] ?? null;

if (!$studentId) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

$query = "CALL SP_GET_INBOX_MESSAGES(?)";

try {
    $inboxResult = (object) $db->callStoredProcedure($query, "i", [$studentId], $mysqli);

    if ($inboxResult->num_rows == 0) {
        echo json_encode(new StudentResponse("failure", error: new ErrorResponse(404, "No inbox messages found")));
        return;
    }

    $inboxData = $inboxResult->fetch_all(MYSQLI_ASSOC);
    $mysqli->close();

    echo json_encode(new StudentResponse("success", $inboxData));
} catch (Throwable $err) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}
