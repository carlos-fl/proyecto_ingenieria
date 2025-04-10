<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';

Request::isWrongRequestMethod('GET');

session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["ID_STUDENT"])) {
    http_response_code(401);
    echo json_encode(["status" => "failure", "message" => "User not logged in", "code" => 401]);
    return;
}

$studentId = $_SESSION["ID_STUDENT"];

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_GET_BOOKS_BY_STUDENT(?)";
    $result = $db->callStoredProcedure($query, "i", [$studentId], $mysqli);

    if ($result->num_rows === 0) {
        $mysqli->close();
        echo json_encode(["status" => "success", "data" => []]);
        return;
    }

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $row['tags'] = !empty($row['tags']) ? explode(', ', $row['tags']) : [];
        $books[] = $row;
    }

    $mysqli->close();

    echo json_encode(["status" => "success", "data" => $books]);
} catch (Throwable $err) {
    $mysqli->close();
    http_response_code(500);
    echo json_encode([
        "status" => "failure",
        "message" => "Error fetching books: " . $err->getMessage(),
        "code" => 500
    ]);
}