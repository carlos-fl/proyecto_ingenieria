<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['ID_STUDENT'])) {
    echo json_encode(["status" => "error", "message" => "Usuario no autenticado."]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
if (!$input) {
    echo json_encode(["status" => "error", "message" => "Datos inválidos."]);
    exit;
}

$phone = $input['phone'] ?? null;
$description = $input['description'] ?? null;

if ($phone === null || $description === null) {
    echo json_encode(["status" => "error", "message" => "Faltan datos requeridos."]);
    exit;
}

require_once '../../database/database.php';
$database = Database::getDatabaseInstace();
$mysqli = $database->getConnection();

$studentId = $_SESSION['ID_STUDENT'];

$query = "CALL sp_update_student_personal_info(?, ?, ?)";
$spResult = $database->callStoredProcedure($query, "iss", [$studentId, $phone, $description], $mysqli);

if (!$spResult) {
    echo json_encode(["status" => "error", "message" => "Error al ejecutar el stored procedure."]);
    $mysqli->close();
    exit;
}

$mysqli->close();
echo json_encode(["status" => "success"]);