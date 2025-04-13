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

$senderId = (int) $_SESSION["ID_STUDENT"];
$request = json_decode(file_get_contents("php://input"));

$receiverEmail = trim($request->receiverEmail ?? '');

if (empty($receiverEmail)) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Missing receiver email")));
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_SEND_CONTACT_REQUEST(?, ?)";
    $db->callStoredProcedure($query, "is", [$senderId, $receiverEmail], $mysqli);

    $mysqli->close();
    echo json_encode(new StudentResponse("success"));
} catch (mysqli_sql_exception $e) {
    $mysqli->close();
    $message = $e->getMessage();

    $errorCode = str_contains($message, 'Correo institucional no encontrado') ? 404 :
                 (str_contains($message, 'Ya existe una solicitud') ? 409 : 500);

    echo json_encode(new StudentResponse("failure", error: new ErrorResponse($errorCode, $message)));
} catch (Throwable $err) {
    $mysqli->close();
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}