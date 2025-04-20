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

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    // Obtener índice global
    $stmtGlobal = $mysqli->prepare("CALL SP_GET_GLOBAL_INDEX(?, @globalIndex)");
    $stmtGlobal->bind_param("i", $studentId);
    $stmtGlobal->execute();
    $stmtGlobal->close();
    $mysqli->next_result();

    $resultGlobal = $mysqli->query("SELECT @globalIndex AS globalIndex");
    $globalIndex = $resultGlobal->fetch_assoc()["globalIndex"];
    $mysqli->next_result();

    // Obtener índice del período más reciente
    $stmtPeriod = $mysqli->prepare("CALL SP_GET_PERIOD_INDEX(?, NULL, @periodIndex)");
    $stmtPeriod->bind_param("i", $studentId);
    $stmtPeriod->execute();
    $stmtPeriod->close();
    $mysqli->next_result();

    $resultPeriod = $mysqli->query("SELECT @periodIndex AS periodIndex");
    $periodIndex = $resultPeriod->fetch_assoc()["periodIndex"];
    $mysqli->close();

    echo json_encode([
        "status" => "success",
        "data" => [
            "globalIndex" => $globalIndex,
            "periodIndex" => $periodIndex
        ]
    ]);
} catch (Throwable $err) {
    $mysqli->close();
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage())));
}
