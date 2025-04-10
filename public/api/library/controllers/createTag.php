<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';

Request::isWrongRequestMethod('POST');
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
$tagName = trim($request->tagName ?? '');

if ($tagName === '') {
    http_response_code(400);
    echo json_encode(["status" => "failure", "message" => "Missing tag name"]);
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $stmt = $mysqli->prepare("CALL SP_CREATE_TAG(?, @tagId)");
    $stmt->bind_param("s", $tagName);
    $stmt->execute();
    $stmt->close();

    $res = $mysqli->query("SELECT @tagId AS tagId");
    $tagId = $res->fetch_assoc()['tagId'];
    $mysqli->close();

    echo json_encode(["status" => "success", "tagId" => (int)$tagId]);

} catch (Throwable $err) {
    $mysqli->close();
    http_response_code(500);
    echo json_encode(["status" => "failure", "message" => "Error creating tag: " . $err->getMessage()]);
}