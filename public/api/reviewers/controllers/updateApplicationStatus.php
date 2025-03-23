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

    // JSON con APPLICATION_CODE, STATUS y opcionalmente REASON
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['APPLICATION_CODE']) || !isset($data['STATUS'])) {
        throw new Exception("Faltan parámetros requeridos");
    }

    $applicationId = $data['APPLICATION_CODE'];
    $newStatus = $data['STATUS'];

    $reason = isset($data['REASON']) ? $data['REASON'] : null;

    // Actualizar el estado de la solicitud
    $stmt = $conn->prepare("UPDATE TBL_APPLICATIONS SET STATUS = ? WHERE APPLICATION_CODE = ?");
    $stmt->bind_param("si", $newStatus, $applicationId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Solicitud actualizada correctamente"
        ]);
    } else {
        echo json_encode([
            "status" => "failure",
            "error" => [
                "errorCode" => "400",
                "errorMessage" => "No se pudo actualizar la solicitud o ya fue procesada"
            ]
        ]);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "failure",
        "error" => [
            "errorCode" => "500",
            "errorMessage" => $e->getMessage()
        ]
    ]);
}
