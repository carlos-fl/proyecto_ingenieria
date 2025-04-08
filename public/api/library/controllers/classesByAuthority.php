<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';
include_once __DIR__ . '/../../../../services/teachers/services/Teachers.php';

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

$teacherNumber = $_SESSION["TEACHER_NUMBER"] ?? TeacherService::getTeacherNumber($_SESSION["USER_ID"]);
$_SESSION["TEACHER_NUMBER"] = $teacherNumber;

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $query = "CALL SP_GET_CLASSES_BY_AUTHORITY(?)";
    $result = $db->callStoredProcedure($query, "i", [$teacherNumber], $mysqli);

    if ($result->num_rows === 0) {
        $mysqli->close();
        echo json_encode(["status" => "success", "data" => []]);
        return;
    }

    $classes = $result->fetch_all(MYSQLI_ASSOC);
    $mysqli->close();

    echo json_encode(["status" => "success", "data" => $classes]);
} catch (Throwable $err) {
    $mysqli->close();
    http_response_code(500);
    echo json_encode([
        "status" => "failure",
        "message" => "Error fetching classes: " . $err->getMessage(),
        "code" => 500
    ]);
}
