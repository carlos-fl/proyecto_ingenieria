<?php
require_once __DIR__ . "/../../../../config/database/Database.php";
require_once __DIR__ . "/../../../../config/env/Environment.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "405", "errorMessage" => "Method Not Allowed"]
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["applicantCode"])) {
    http_response_code(400);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "400", "errorMessage" => "Missing required field 'applicantCode'"]
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
    $stmt = $conn->prepare("CALL ApproveApplication(?)");
    $stmt->bind_param("i", $data["applicantCode"]);
    $stmt->execute();

    echo json_encode(["status" => "success"]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "500", "errorMessage" => $e->getMessage()]
    ]);
}

$conn->close();
?>
