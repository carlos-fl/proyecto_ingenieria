<?php
require_once __DIR__ . "/../../../../config/database/Database.php";
require_once __DIR__ . "/../../../../config/env/Environment.php";
require_once __DIR__ . "/../../../../services/emailNotifications/EmailService.php"; 

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

if (!isset($data["applicationCode"], $data["commentary"]) || empty($data["commentary"])) {
    http_response_code(400);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "400", "errorMessage" => "Missing required fields 'applicationCode' or 'commentary'"]
    ]);
    exit;
}

$applicationCode = $data["applicationCode"];
$commentary = trim($data["commentary"]); 

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
    $checkStmt = $conn->prepare("SELECT EMAIL, FIRST_NAME, LAST_NAME FROM TBL_APPLICATIONS WHERE APPLICATION_CODE = ?");
    $checkStmt->bind_param("i", $applicationCode);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        echo json_encode([
            "status" => "failure",
            "error" => ["errorCode" => "404", "errorMessage" => "Application not found"]
        ]);
        exit;
    }

    $row = $checkResult->fetch_assoc();
    $userEmail = $row["EMAIL"];
    $userName = $row["FIRST_NAME"] . " " . $row["LAST_NAME"];
    $stmt = $conn->prepare("CALL SP_REJECT_APPLICATION(?, ?)");
    $stmt->bind_param("is", $applicationCode, $commentary);

    if ($stmt->execute()) {
        $emailTemplatePath = __DIR__ . "/../../../../services/emailNotifications/emailsBlueprints/applicationReject.html";

        EmailService::sendEmail(
            $userEmail,
            "Tu solicitud ha sido rechazada - UNAH",
            [
                "name" => $userName,
                "application_code" => $applicationCode,
                "commentary" => $commentary
            ],
            $emailTemplatePath
        );

        echo json_encode(["status" => "success"]);
    } else {
        throw new Exception("Database error: " . $stmt->error);
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
