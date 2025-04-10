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
$teacherNumber = $_SESSION["TEACHER_NUMBER"];

$hasTeacherAndCoordinator = in_array("TEACHERS", $roles) && in_array("COORDINATOR", $roles);
$hasTeacherAndDepartmentChair = in_array("TEACHERS", $roles) && in_array("DEPARTMENT_CHAIR", $roles);

if (!($hasTeacherAndCoordinator || $hasTeacherAndDepartmentChair)) {
    http_response_code(403);
    echo json_encode(["status" => "failure", "message" => "Access denied", "code" => 403]);
    return;
}

$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$limit = 6;
$offset = $limit * ($page - 1);

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $countQuery = "
        SELECT COUNT(*) AS total
        FROM TBL_BOOKS b
        INNER JOIN TBL_TEACHERS t ON t.MAJOR_ID = b.MAJOR_ID
        WHERE t.TEACHER_NUMBER = ? AND b.ACTIVE = TRUE
    ";
    $stmt = $mysqli->prepare($countQuery);
    $stmt->bind_param("i", $teacherNumber);
    $stmt->execute();
    $totalResult = $stmt->get_result();
    $totalBooks = $totalResult->fetch_assoc()["total"];
    $totalPages = ceil($totalBooks / $limit);
    $stmt->close();


    $query = "CALL SP_GET_BOOKS_BY_TEACHER_CAREERS(?, ?, ?)";
    $result = $db->callStoredProcedure($query, "iii", [$teacherNumber, $offset, $limit], $mysqli);

    if ($result->num_rows === 0) {
        $mysqli->close();
        echo json_encode(["status" => "success", "data" => [], "totalPages" => $totalPages, "currentPage" => $page]);
        return;
    }

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $row['tags'] = !empty($row['tags']) ? explode(', ', $row['tags']) : [];
        $books[] = $row;
    }

    $mysqli->close();
    echo json_encode([
        "status" => "success",
        "data" => $books,
        "totalPages" => $totalPages,
        "currentPage" => $page
    ]);
} catch (Throwable $err) {
    $mysqli->close();
    http_response_code(500);
    echo json_encode([
        "status" => "failure",
        "message" => "Error fetching books: " . $err->getMessage(),
        "code" => 500
    ]);
}