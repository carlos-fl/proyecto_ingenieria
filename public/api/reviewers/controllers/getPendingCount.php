<?php
require_once __DIR__ . "/../../../../config/database/Database.php";
require_once __DIR__ . "/../../../../config/env/Environment.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(400);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "400", "errorMessage" => "Invalid request method"]
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

$reviewer_id = $_GET["reviewer_id"] ?? null;

if (!$reviewer_id) {
    http_response_code(400);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "400", "errorMessage" => "Missing reviewer_id"]
    ]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT COUNT(*) AS pending_count FROM tbl_applications WHERE status = 0 AND reviewer_id = ?");
    $stmt->bind_param("i", $reviewer_id);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $count = $result->fetch_assoc();
        echo json_encode(["status" => "success", "data" => $count]);
    } else {
        echo json_encode(["status" => "success", "data" => ["pending_count" => 0]]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "500", "errorMessage" => $e->getMessage()]
    ]);
}

$conn->close();
