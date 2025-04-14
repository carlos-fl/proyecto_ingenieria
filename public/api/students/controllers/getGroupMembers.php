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

try {
    $groupId = isset($_GET['groupId']) ? (int) $_GET['groupId'] : null;
    if (!$groupId) {
        echo json_encode(new StudentResponse("failure", error: new ErrorResponse(400, "Missing groupId")));
        return;
    }

    $page = isset($_GET["page"]) ? max(1, (int) $_GET["page"]) : 1;
    $limit = 20;
    $offset = $limit * ($page - 1);

    $db = Database::getDatabaseInstace();
    $mysqli = $db->getConnection();

    $countQuery = "SELECT COUNT(*) AS total FROM TBL_GRUPO_X_STUDENTS WHERE GROUP_ID = ? AND IS_MEMBER = TRUE";
    $stmt = $mysqli->prepare($countQuery);
    $stmt->bind_param("i", $groupId);
    $stmt->execute();
    $countResult = $stmt->get_result();
    $total = $countResult->fetch_assoc()["total"];
    $stmt->close();

    $totalPages = ceil($total / $limit);

    $query = "CALL SP_GET_GROUP_MEMBERS(?, ?, ?)";
    $result = $db->callStoredProcedure($query, "iii", [$groupId, $offset, $limit], $mysqli);

    $members = [];
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }

    $mysqli->close();

    echo json_encode([
        "status" => "success",
        "data" => $members,
        "totalPages" => $totalPages,
        "currentPage" => $page
    ]);
} catch (Throwable $err) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}