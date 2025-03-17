<?php
require_once __DIR__ . "/../../../../../config/database/Database.php";
require_once __DIR__ . "/../../../../../config/env/Environment.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "GET" || !isset($_GET["applicationCode"]) || empty($_GET["applicationCode"])) {
    http_response_code(400);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "400", "errorMessage" => "Missing or invalid applicationCode"]
    ]);
    exit;
}

$applicationCode = $_GET["applicationCode"];

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
    $stmt = $conn->prepare("CALL SP_GET_APPLICANT_BY_CODE(?)");
    $stmt->bind_param("i", $applicationCode);
    $stmt->execute();
    
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $applicant = $result->fetch_assoc();
        echo json_encode(["status" => "success", "data" => $applicant]);
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "failure",
            "error" => ["errorCode" => "404", "errorMessage" => "Applicant not found"]
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
