<?php

require_once __DIR__ . '/../../../../config/database/Database.php';
require_once __DIR__ . '/../../../../config/env/Environment.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
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

    // Consulta para contar las solicitudes pendientes.
    $sql = "SELECT COUNT(*) AS count FROM TBL_APPLICATIONS WHERE STATUS = 'pending'";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Error al ejecutar la consulta: " . $conn->error);
    }

    $row = $result->fetch_assoc();
    $count = intval($row["count"]);

    echo json_encode([
        "status" => "success",
        "count" => $count
    ]);

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
