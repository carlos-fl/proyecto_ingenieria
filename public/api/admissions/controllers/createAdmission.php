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


$env = Environment::getVariables();


$db = new Database(
    $env["DB_HOST"],
    $env["DB_NAME"],
    $env["DB_USER"],
    $env["DB_PASSWORD"],
    intval($env["DB_PORT"])
);

$conn = $db->getConnection();


$data = json_decode(file_get_contents("php://input"), true);


if (
    !isset($data["firstName"], $data["lastName"], $data["id"], $data["phoneNumber"], 
    $data["email"], $data["gender"], $data["primaryMajor"], $data["secondaryMajor"], 
    $data["comment"], $data["certificate"])
) {
    http_response_code(400);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "400", "errorMessage" => "Missing required fields"]
    ]);
    exit;
}

try {

    $stmt = $conn->prepare("CALL SP_Create_Admission(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssssiis",
        $data["firstName"],
        $data["lastName"],
        $data["id"],
        $data["phoneNumber"],
        $data["email"],
        $data["gender"],
        $data["primaryMajor"],
        $data["secondaryMajor"],
        $data["comment"],
        $data["certificate"]
    );

    if ($stmt->execute()) {
        $result = $stmt->get_result()->fetch_assoc();
        echo json_encode(["status" => "success", "application_code" => $result["application_code"]]);
    } else {
        throw new Exception("Database error");
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
