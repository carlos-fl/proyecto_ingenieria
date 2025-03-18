<?php
require_once __DIR__ . "/../../../../config/database/Database.php";
require_once __DIR__ . "/../../../../config/env/Environment.php";
require_once __DIR__ . "/../../../../services/contentManagement/ContentManagement.php";
require_once __DIR__ . "/../../../../services/emailNotifications/EmailService.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
    http_response_code(405);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "405", "errorMessage" => "Method Not Allowed"]
    ]);
    exit;
}

parse_str(file_get_contents("php://input"), $data);
if (empty($data)) {
    $data = $_POST; 
}

if (!isset($data["applicationCode"]) || empty($data["applicationCode"])) {
    http_response_code(400);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "400", "errorMessage" => "Missing required field: applicationCode"]
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
    $checkStmt = $conn->prepare("SELECT EMAIL, CERTIFICATE_FILE FROM TBL_APPLICATIONS WHERE APPLICATION_CODE = ?");
    $checkStmt->bind_param("i", $data["applicationCode"]);
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
    $oldCertificatePath = $row["CERTIFICATE_FILE"];
    $userEmail = $row["EMAIL"];

    if (isset($_FILES["certificate"]) && $_FILES["certificate"]["error"] === 0) {
        $newCertificatePath = ContentManagement::saveFile($_FILES["certificate"]);

        if (!empty($oldCertificatePath) && file_exists(__DIR__ . "/../../.." . $oldCertificatePath)) {
            unlink(__DIR__ . "/../../.." . $oldCertificatePath);
        }
    } else {
        $newCertificatePath = $oldCertificatePath;
    }

    $stmt = $conn->prepare("CALL SP_UPDATE_ADMISSION(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "isssssssiis",  
        $data["applicationCode"],
        $data["firstName"],
        $data["lastName"],
        $data["dni"],  
        $data["phoneNumber"],
        $data["email"],
        $data["gender"],
        $data["primaryMajor"],
        $data["secondaryMajor"],
        $data["comment"],
        $newCertificatePath
    );

    if ($stmt->execute()) {
        $emailTemplatePath = __DIR__ . "/../../../../../services/emailNotifications/emailsBlueprints/admissionUpdate.html";

        EmailService::sendEmail(
            $userEmail,
            "Actualización de solicitud - UNAH",
            [
                "name" => $data["firstName"] . " " . $data["lastName"],
                "application_code" => $data["applicationCode"]
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
