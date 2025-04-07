<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';

Request::isWrongRequestMethod('POST');
header("Content-Type: application/json");

$bookId = $_POST["bookId"] ?? null;

if (!$bookId) {
    http_response_code(400);
    echo json_encode(["status" => "failure", "message" => "Missing book ID", "code" => 400]);
    return;
}

if (!in_array($_SESSION["ROL_ID"], [5, 6])) {
    http_response_code(403);
    echo json_encode(["status" => "failure", "message" => "Acceso denegado", "code" => 403]);
    return;
}


$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_DEACTIVATE_BOOK(?)";
    $db->callStoredProcedure($query, "i", [$bookId], $mysqli);

    $mysqli->close();
    echo json_encode(["status" => "success", "message" => "Book deactivated successfully"]);

} catch (Throwable $err) {
    $mysqli->close();
    http_response_code(500);
    echo json_encode([
        "status" => "failure",
        "message" => "Error deactivating book: " . $err->getMessage(),
        "code" => 500
    ]);
}