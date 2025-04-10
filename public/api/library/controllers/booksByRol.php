<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';

Request::isWrongRequestMethod('GET');
session_start();
header("Content-Type: application/json");

if (empty($_SESSION) || !isset($_SESSION["ROLES"], $_SESSION["TEACHER_NUMBER"])) {
    http_response_code(401);
    echo json_encode(["status" => "failure", "message" => "Unauthorized", "code" => 401]);
    return;
}

$roles = $_SESSION["ROLES"];

$hasTeacherAndCoordinator = in_array("TEACHERS", $roles) && in_array("COORDINATOR", $roles);
$hasTeacherAndDepartmentChair = in_array("TEACHERS", $roles) && in_array("DEPARTMENT_CHAIR", $roles);

if (!($hasTeacherAndCoordinator || $hasTeacherAndDepartmentChair)) {
    http_response_code(403);
    echo json_encode(["status" => "failure", "message" => "Access denied", "code" => 403]);
    return;
}

$teacherNumber = $_SESSION["TEACHER_NUMBER"];

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_GET_BOOKS_BY_TEACHER_CAREERS(?)";
    $result = $db->callStoredProcedure($query, "i", [$teacherNumber], $mysqli);

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
