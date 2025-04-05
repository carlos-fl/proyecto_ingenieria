<?php
include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../config/database/Database.php';

// Verifica que el método sea POST
Request::isWrongRequestMethod('POST');

session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["ID_STUDENT"])) {
    http_response_code(401);
    echo json_encode([
        "status" => "failure", 
        "message" => "User not logged in", 
        "code" => 401
    ]);
    return;
}

$studentId = $_SESSION["ID_STUDENT"];

// Decodificar la entrada JSON
$input = json_decode(file_get_contents("php://input"), true);
$phone = isset($input["phone"]) ? trim($input["phone"]) : null;
$description = isset($input["description"]) ? trim($input["description"]) : null;

// Si ambos campos son nulos o vacíos, se rechaza la petición
if ((is_null($phone) || $phone === "") && (is_null($description) || $description === "")) {
    http_response_code(400);
    echo json_encode([
        "status" => "failure", 
        "message" => "Debe proporcionar al menos un campo para actualizar", 
        "code" => 400
    ]);
    return;
}

$db = Database::getDatabaseInstace();
$mysqli = $db->getConnection();

try {
    // Se asume que el procedimiento almacenado SP_UPDATE_STUDENT_PROFILE
    // puede recibir valores NULL para indicar que ese campo no se actualizará.
    $query = "CALL SP_UPDATE_STUDENT_PROFILE(?, ?, ?)";
    $db->callStoredProcedure(
        $query,
        "iss",
        [$studentId, $phone, $description],
        $mysqli
    );
    $mysqli->close();

    echo json_encode(["status" => "success"]);
} catch (Throwable $err) {
    $mysqli->close();
    http_response_code(500);
    echo json_encode([
        "status" => "failure",
        "message" => "Error actualizando perfil: " . $err->getMessage(),
        "code" => 500
    ]);
}