<?php

require_once __DIR__ . '/../../../../config/database/Database.php';
require_once __DIR__ . '/../../../../config/env/Environment.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => "failure",
        "error" => [
            "errorCode" => "405",
            "errorMessage" => "Method Not Allowed"
        ]
    ]);
    exit;
}

try {
    $env = Environment::getVariables();
    $db = new Database(
        $env["DB_HOST"], 
        $env["DB_NAME"], 
        $env["DB_USER"], 
        $env["DB_PASSWORD"], 
        intval($env["DB_PORT"])
    );
    $conn = $db->getConnection();

    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['APPLICATION_CODE']) || !isset($data['STATUS'])) {
        throw new Exception("Faltan parámetros requeridos");
    }

    $applicationCode = $data['APPLICATION_CODE'];
    $status = $data['STATUS'];
    $reason = isset($data['REASON']) ? $data['REASON'] : null;

    $stmt = $conn->prepare("CALL SP_UPDATE_APPLICATION_STATUS(?, ?, ?)");
    $stmt->bind_param("sis", $applicationCode, $status, $reason);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "Application status updated successfully"]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "failure",
            "error" => ["errorCode" => "404", "errorMessage" => "Application not found"]
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "500", "errorMessage" => $e->getMessage()]
    ]);
}

$conn->close();
?>
