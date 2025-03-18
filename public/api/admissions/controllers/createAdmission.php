<?php
require_once __DIR__ . "/../../../../config/database/Database.php";
require_once __DIR__ . "/../../../../config/env/Environment.php";
require_once __DIR__ . "/../../../../services/contentManagement/ContentManagement.php";
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

$env = Environment::getVariables();
$db = new Database(
    $env["DB_HOST"],
    $env["DB_NAME"],
    $env["DB_USER"],
    $env["DB_PASSWORD"],
    intval($env["DB_PORT"])
);
$conn = $db->getConnection();

$data = $_POST;
if (empty($data)) {
    $data = json_decode(file_get_contents("php://input"), true);
    file_put_contents("/tmp/debug_fixed_post.log", print_r($data, true));
}

$required_fields = ["firstName", "lastName", "dni", "phoneNumber", "email", "gender", "primaryMajor", "secondaryMajor", "comment"];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        http_response_code(400);
        echo json_encode([
            "status" => "failure",
            "error" => ["errorCode" => "400", "errorMessage" => "aqui esta error: $field"]
        ]);
        exit;
    }
}

if (!isset($_FILES["certificate"]) || $_FILES["certificate"]["error"] > 0) {
    http_response_code(400);
    echo json_encode([
        "status" => "failure",
        "error" => ["errorCode" => "400", "errorMessage" => "Certificate file is required or invalid"]
    ]);
    exit;
}

try {
    $certificatePath = ContentManagement::saveFile($_FILES["certificate"]);

    $certificatePath = str_replace(__DIR__ . "/../../..", "", $certificatePath);

    $stmt = $conn->prepare("CALL SP_CREATE_ADMISSION(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssssiis",
        $data["firstName"],      
        $data["lastName"],       
        $data["dni"],           
        $data["phoneNumber"],   
        $data["email"],          
        $data["gender"],        
        $data["primaryMajor"],   
        $data["secondaryMajor"], 
        $data["comment"],        
        $certificatePath  
    );

    if ($stmt->execute()) {
        $result = $stmt->get_result()->fetch_assoc();
        $applicationCode = $result["APPLICATION_CODE"];
        $emailTemplatePath = "admissionSent.html";

        EmailService::sendEmail(
            $data["email"],
            "Confirmación de solicitud - UNAH",
            [
                "name" => $data["firstName"] . " " . $data["lastName"],
                "application_code" => $applicationCode
            ],
            $emailTemplatePath
        );

        echo json_encode(["status" => "success", "application_code" => $applicationCode]);
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
