<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';

Request::isWrongRequestMethod('GET');

header("Content-Type: application/json");

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_GET_ALL_TAGS()";
    $tags = $db->callStoredProcedure($query, "", [], $mysqli);

    if ($tags->num_rows === 0) {
        $mysqli->close();
        echo json_encode(["status" => "success", "data" => []]);
        return;
    }

    $tagsData = $tags->fetch_all(MYSQLI_ASSOC);
    $mysqli->close();

    echo json_encode(["status" => "success", "data" => $tagsData]);
    
} catch (Throwable $err) {
    $mysqli->close();
    http_response_code(500);
    echo json_encode([
        "status" => "failure",
        "message" => "Error fetching tags: " . $err->getMessage(),
        "code" => 500
    ]);
}