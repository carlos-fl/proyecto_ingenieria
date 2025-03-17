<?php
require_once __DIR__ . "/../../../../../config/database/Database.php";
require_once __DIR__ . "/../../../../../config/env/Environment.php";


header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "GET" || !isset($_GET["status"])) {
    http_response_code(400);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "400", "errorMessage" => "Missing or invalid 'status' parameter"]
    ]);
    exit;
}

$status = trim(strtolower($_GET["status"])); 

$validStatuses = ["pending", "approved", "rejected"];

if (!in_array($status, $validStatuses, true)) {
    http_response_code(400);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "400", "errorMessage" => "Invalid status value"]
    ]);
    exit;
}

$env = Environment::getVariables();
$db = new Database(
    $env["DB_HOST"],
    $env["DB_NAME"],
    $env["DB_USER"],
    $env["DB_PASSWORD"],
    intval($env["DB_PORT"])
);

$conn = $db->getConnection();

try {
    $stmt = $conn->prepare("CALL SP_GET_APPLICATIONS_BY_STATUS(?)");
    $stmt->bind_param("s", $status);
    $stmt->execute();

    $result = $stmt->get_result();
    $applications = [];

    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }

    echo json_encode(["status" => "success", "data" => $applications]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "500", "errorMessage" => $e->getMessage()]
    ]);
}

$conn->close();
?>