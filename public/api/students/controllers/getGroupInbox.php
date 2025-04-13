<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';
include_once __DIR__ . '/../../../../services/students/types/StudentResponse.php';

session_start();
Request::isWrongRequestMethod('GET');
header("Content-Type: application/json");

if (empty($_SESSION) || !isset($_SESSION["ID_STUDENT"])) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

$studentId = $_SESSION["ID_STUDENT"];
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$limit = 30;
$offset = $limit * ($page - 1);

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

// Total de grupos del estudiante
$countQuery = "SELECT COUNT(*) AS total FROM TBL_GRUPO_X_STUDENTS WHERE STUDENT_ID = ? AND IS_MEMBER = TRUE";
$stmt = $mysqli->prepare($countQuery);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$totalResult = $stmt->get_result();
$totalGroups = $totalResult->fetch_assoc()["total"];
$totalPages = ceil($totalGroups / $limit);
$stmt->close();

try {
    $query = "CALL SP_GET_GROUP_INBOX_MESSAGES(?, ?, ?)";
    $result = $db->callStoredProcedure($query, "iii", [$studentId, $offset, $limit], $mysqli);

    if ($result->num_rows === 0) {
        $mysqli->close();
        echo json_encode([
            "status" => "success",
            "data" => [],
            "totalPages" => $totalPages,
            "currentPage" => $page
        ]);
        return;
    }

    $groups = $result->fetch_all(MYSQLI_ASSOC);
    $mysqli->close();

    echo json_encode([
        "status" => "success",
        "data" => $groups,
        "totalPages" => $totalPages,
        "currentPage" => $page
    ]);

} catch (Throwable $err) {
    $mysqli->close();
    http_response_code(500);
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}