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

$studentId = (int) $_SESSION["ID_STUDENT"];
$page = isset($_GET["page"]) ? max(1, (int) $_GET["page"]) : 1;
$limit = 20;
$offset = $limit * ($page - 1);

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    $countQuery = "
        SELECT COUNT(*) AS total
        FROM TBL_CONTACTS c
        WHERE c.FRIEND_ID = ? AND c.STATUS = 'PENDING'
    ";
    $stmt = $mysqli->prepare($countQuery);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $totalResult = $stmt->get_result();
    $totalRequests = $totalResult->fetch_assoc()["total"];
    $totalPages = ceil($totalRequests / $limit);
    $stmt->close();

    $query = "CALL SP_GET_PENDING_CONTACT_REQUESTS(?, ?, ?)";
    $result = $db->callStoredProcedure($query, "iii", [$studentId, $offset, $limit], $mysqli);

    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }

    $mysqli->close();

    echo json_encode([
        "status" => "success",
        "data" => $requests,
        "totalPages" => $totalPages,
        "currentPage" => $page
    ]);
} catch (Throwable $err) {
    $mysqli->close();
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}