<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';
include_once __DIR__ . '/../../../../services/students/types/PostResponse.php';

session_start();
Request::isWrongRequestMethod('POST');

if (empty($_SESSION)) {
    echo json_encode(new PostResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

$request = getJsonData();

$senderId = $_SESSION["ID_STUDENT"];
$receiverId = $request->receiverId ?? null;
$receiverType = strtoupper(trim($request->receiverType ?? ''));
$content = trim($request->content ?? '');

if (!$receiverId || empty($content) || !in_array($receiverType, ['STUDENT', 'GROUP'])) {
    echo json_encode(new PostResponse("failure", error: new ErrorResponse(400, "Missing or invalid required fields")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

$query = "CALL SP_SEND_MESSAGE(?, ?, ?, ?)";

try {
    $db->callStoredProcedure($query, "iiss", [$senderId, $receiverId, $receiverType, $content], $mysqli);
    $mysqli->close();

    echo json_encode(new PostResponse("success"));

} catch (Throwable $err) {
    echo json_encode(new PostResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}