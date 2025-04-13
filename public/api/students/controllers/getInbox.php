<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';
include_once __DIR__ . '/../../../../services/students/types/StudentResponse.php';

session_start();
Request::isWrongRequestMethod('GET');

if (empty($_SESSION) || !isset($_SESSION["ID_STUDENT"])) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

$studentId = $_SESSION["ID_STUDENT"];
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 10;
$offset = $limit * ($page - 1);

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

// Primero contar cuántos chats únicos hay
$countQuery = "
    SELECT COUNT(DISTINCT IF(SENDER_ID = ?, RECEIVER_ID, SENDER_ID)) AS total
    FROM TBL_MESSAGES
    WHERE ? IN (SENDER_ID, RECEIVER_ID)
";
$stmt = $mysqli->prepare($countQuery);
$stmt->bind_param("ii", $studentId, $studentId);
$stmt->execute();
$totalResult = $stmt->get_result();
$totalMessages = $totalResult->fetch_assoc()["total"];
$totalPages = ceil($totalMessages / $limit);
$stmt->close();


// Obtener los chats paginados
$query = "CALL SP_GET_INBOX_MESSAGES(?, ?, ?)";
$result = $db->callStoredProcedure($query, "iii", [$studentId, $offset, $limit], $mysqli);

if ($result->num_rows === 0) {
    $mysqli->close();
    echo json_encode(new StudentResponse("success", []));
    return;
}

$chats = $result->fetch_all(MYSQLI_ASSOC);
$mysqli->close();

echo json_encode([
    "status" => "success",
    "data" => $chats,
    "totalPages" => $totalPages,
    "currentPage" => $page
]);