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
$page = isset($_GET["page"]) ? max(1, (int)$_GET["page"]) : 1;
$limit = 10;
$offset = $limit * ($page - 1);

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

// Calcular total de grupos
$countQuery = "
    SELECT COUNT(DISTINCT g.GROUP_ID) AS total
    FROM TBL_CHAT_GROUPS g
    INNER JOIN TBL_GRUPO_X_STUDENTS gs ON g.GROUP_ID = gs.GROUP_ID
    WHERE gs.STUDENT_ID = ? AND gs.IS_MEMBER = TRUE
";
$stmt = $mysqli->prepare($countQuery);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$totalResult = $stmt->get_result();
$totalGroups = $totalResult->fetch_assoc()["total"];
$totalPages = ceil($totalGroups / $limit);
$stmt->close();

// Ejecutar SP paginado
$query = "CALL SP_GET_STUDENT_GROUPS(?, ?, ?)";
$result = $db->callStoredProcedure($query, "iii", [$studentId, $offset, $limit], $mysqli);

if ($result->num_rows === 0) {
    $mysqli->close();
    echo json_encode(new StudentResponse("success", [], null));
    return;
}

$groupsData = $result->fetch_all(MYSQLI_ASSOC);
$mysqli->close();

echo json_encode([
    "status" => "success",
    "data" => $groupsData,
    "totalPages" => $totalPages,
    "currentPage" => $page,
    "error" => null
]);