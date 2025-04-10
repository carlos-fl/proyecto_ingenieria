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

$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$limit = 6;
$offset = $limit * ($page - 1);

$studentId = $_SESSION["ID_STUDENT"];

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    // Necesitamos obtener el total de libros para calcular el total de páginas que necesitare el front
    $countQuery = "
        SELECT COUNT(*) AS total
        FROM TBL_BOOKS b
        WHERE b.MAJOR_ID IN (
            SELECT MAJOR_ID FROM TBL_MAJORS_X_STUDENTS WHERE ID_STUDENT = ?
        ) AND b.ACTIVE = TRUE
    ";

    $stmt = $mysqli->prepare($countQuery);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $totalResult = $stmt->get_result();
    $totalBooks = $totalResult->fetch_assoc()["total"];
    $totalPages = ceil($totalBooks / $limit);
    $stmt->close();

    $query = "CALL SP_GET_BOOKS_BY_STUDENT_MAJOR(?, ?, ?)";
    $result = $db->callStoredProcedure($query, "iii", [$studentId, $offset, $limit], $mysqli);

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
        "message" => "Error fetching books by major: " . $err->getMessage(),
        "code" => 500
    ]);
}