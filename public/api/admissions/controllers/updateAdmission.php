<?php
require_once __DIR__ . "/../../../../../config/database/Database.php";
require_once __DIR__ . "/../../../../../config/env/Environment.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
    http_response_code(405);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "405", "errorMessage" => "Method Not Allowed"]
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data["applicationCode"], $data["firstName"], $data["lastName"], $data["dni"], 
    $data["phoneNumber"], $data["email"], $data["gender"], $data["primaryMajor"], 
    $data["secondaryMajor"], $data["comment"], $data["certificate"])
) {
    http_response_code(400);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "400", "errorMessage" => "Missing required fields"]
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
    $checkStmt = $conn->prepare("SELECT APPLICATION_CODE FROM TBL_APPLICATIONS WHERE APPLICATION_CODE = ?");
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
        $data["certificate"]
    );

    if ($stmt->execute()) {
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

