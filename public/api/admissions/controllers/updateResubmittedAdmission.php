<?php
require_once __DIR__ . "/../../../../config/database/Database.php";
require_once __DIR__ . "/../../../../config/env/Environment.php";
require_once __DIR__ . "/../../../../services/contentManagement/ContentManagement.php";
require_once __DIR__ . "/../../../../services/emailNotifications/EmailService.php";
require_once __DIR__ . '/../../../../utils/classes/Regex.php';
require_once __DIR__ . '/../../../../services/admissions/AdmissionService.php';

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

$required_fields = ["firstName", "lastName", "dni", "phoneNumber", "email", "gender", "comment"];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        http_response_code(400);
        echo json_encode([
            "status" => "failure",
            "error" => ["errorCode" => "400", "errorMessage" => "aqui esta error: $field", "vals" => $data]
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

    if (!Regex::isValidIdentification($data['dni'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "failure",
            "error" => [
                "errorCode" => 400,
                "errorMessage" => "Invalid identification"
            ]
        ]);
        return;
    }

    $identification = Regex::isValidDNI($data['dni']) ? 1 : 2; 
    $cleanDNI = explode('-', $data["dni"], 3);
    $dni = $cleanDNI[0] . $cleanDNI[1] . $cleanDNI[2];
    $stmt = $conn->prepare("CALL SP_UPDATE_SUBMITTED_ADMISSION(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssssssssiiss",
        $data["firstName"],      
        $data["lastName"],       
        $dni,           
        $data["phoneNumber"],   
        $data["email"],          
        $data["gender"],        
        $data["comment"],        
        $certificatePath,
        $identification,
        $data['applicationID'],
        $data['applicationCODE'],
        $data['token']
    );

    if ($stmt->execute()) {
        $result = $stmt->get_result()->fetch_assoc();
        $emailTemplatePath = "resubmission.html";

        EmailService::sendEmail(
            $data["email"],
            "Confirmación de Reenvío - UNAH",
            [
                "name" => $data["firstName"] . " " . $data["lastName"]
            ],
            $emailTemplatePath
        );

        http_response_code(200);
        echo json_encode(["status" => "success", "application_code" => $data['applicationCODE']]);
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

