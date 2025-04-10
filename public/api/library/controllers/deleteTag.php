<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';

Request::isWrongRequestMethod('DELETE');
session_start();
header("Content-Type: application/json");

$roles = $_SESSION["ROLES"] ?? [];
$hasAuthority = in_array("TEACHERS", $roles) && (in_array("COORDINATOR", $roles) || in_array("DEPARTMENT_CHAIR", $roles));

if (!$hasAuthority) {
    http_response_code(403);
    echo json_encode(["status" => "failure", "message" => "Access denied"]);
    return;
}

$request = getJsonData();
$tagId = $request->tagId ?? null;

if (!$tagId) {
    http_response_code(400);
    echo json_encode(["status" => "failure", "message" => "Missing tag ID"]);
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $stmt = $mysqli->prepare("CALL SP_DELETE_TAG(?)");
    $stmt->bind_param("i", $tagId);
    $stmt->execute();
    $stmt->close();

    $mysqli->close();

    echo json_encode(["status" => "success", "message" => "Tag deleted successfully"]);

} catch (Throwable $err) {
    $mysqli->close();
    http_response_code(500);
    echo json_encode(["status" => "failure", "message" => "Error deleting tag: " . $err->getMessage()]);
}