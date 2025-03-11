<?php
require_once __DIR__ . "/../../../../config/database/Database.php";
require_once __DIR__ . "/../../../../config/env/Environment.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "GET" || !isset($_GET["applicant-code"])) {
    http_response_code(400);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "400", "errorMessage" => "Missing or invalid applicant-code"]
    ]);
    exit;
}

$applicant_code = $_GET["applicant-code"];

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
    $stmt = $conn->prepare("CALL SP_Get_Applicant_By_Code(?)");
    $stmt->bind_param("i", $applicant_code);
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
