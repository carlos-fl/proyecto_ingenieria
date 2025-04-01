<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';
include_once __DIR__ . '/../../../../services/students/types/StudentResponse.php';

session_start();
Request::isWrongRequestMethod('POST');

if (empty($_SESSION)) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

// Obtener los datos JSON de la solicitud
$request = getJsonData();

$senderId = $_SESSION["ID_STUDENT"];
$receiverId = $request->receiverId ?? null;
$receiverType = strtoupper(trim($request->receiverType ?? ''));
$content = trim($request->content ?? '');

if (!$receiverId || empty($content) || !in_array($receiverType, ['STUDENT', 'GROUP'])) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Missing or invalid required fields")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

$query = "CALL SP_SEND_MESSAGE(?, ?, ?, ?)";

try {
    $db->callStoredProcedure($query, "iiss", [$senderId, $receiverId, $receiverType, $content], $mysqli);
    $mysqli->close();

    echo json_encode(new StudentResponse("success"));
} catch (Throwable $err) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}

// Función para obtener los datos JSON de la solicitud
function getJsonData()
{
    $data = file_get_contents("php://input");
    return json_decode($data);
}
