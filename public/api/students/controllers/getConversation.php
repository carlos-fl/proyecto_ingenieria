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
$otherStudentId = $_GET['otherStudentId'] ?? null;

if (!$studentId || !$otherStudentId) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Missing parameters")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

$query = "CALL SP_GET_CONVERSATION(?, ?)";

try {
    $conversation = (object) $db->callStoredProcedure($query, "ii", [$studentId, $otherStudentId], $mysqli);

    if ($conversation->num_rows == 0) {
        echo json_encode(new StudentResponse("failure", error: new ErrorResponse(404, "No messages found between the users")));
        return;
    }

    $conversationData = $conversation->fetch_all(MYSQLI_ASSOC);
    $mysqli->close();

    echo json_encode(new StudentResponse("success", $conversationData));
} catch (Throwable $err) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}
