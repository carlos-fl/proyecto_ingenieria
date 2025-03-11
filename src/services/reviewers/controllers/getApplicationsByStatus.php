<?php
require_once __DIR__ . "/../../../../config/database/Database.php";
require_once __DIR__ . "/../../../../config/env/Environment.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "GET" || !isset($_GET["requests"])) {
    http_response_code(400);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "400", "errorMessage" => "Missing or invalid 'requests' parameter"]
    ]);
    exit;
}

$status = $_GET["requests"];

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
    $stmt = $conn->prepare("CALL SP_Get_Applications_By_Status(?)");
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
