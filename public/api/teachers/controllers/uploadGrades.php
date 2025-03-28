<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../utils/classes/Response.php';
include_once __DIR__ . '/../../../../utils/classes/CSVHandler.php';
include_once __DIR__ . '/../../../../services/resources/services/Resources.php';
include_once __DIR__ . '/../../../../services/teachers/services/Teachers.php';
include_once __DIR__ . '/../.././../../config/env/Environment.php';
include_once __DIR__ . '/../../../../utils/classes/Encrypt.php';

session_start();
header("Content-Type: application/json");

Request::isWrongRequestMethod('POST');

// Validar que existan los datos que se necesitan
if (!isset($_POST['sectionId'])) {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => 400,
        'errorMessage' => 'Bad Request. No se recibió el sectionId.'
    ]));
    return;
}

if (!isset($_SESSION["TEACHER_NUMBER"])){
    http_response_code(401);
    echo json_encode(new GetResponse("failure", ["errorCode" => 401, "errorMessage" => "Unathorized"]));
    return;
}


if (!isset($_FILES["csv"])){
    http_response_code(400);
    echo json_encode(new GetResponse("failure", ["errorCode" => 400, "errorMessage" => "No se recibió el archivo CSV"]));
    return;
}

$sectionIdCryptogram = $_POST['sectionId'];
$decryptionIv = $_SESSION[$sectionIdCryptogram];
$csvFile = $_FILES["csv"];
$teacherNumber = $_SESSION["TEACHER_NUMBER"];
$userId = $_SESSION["USER_ID"];

if (!$decryptionIv){
    http_response_code(400);
    echo json_encode(new GetResponse("failure", ["errorCode" => 400, "errorMessage" => "Número de sección inválido"]));
    return;
}
// Desencriptar el id
$env = Environment::getVariables();
$encryption = new Encryption($env["CYPHER_ALGO"], $env["CYPHER_KEY"]);
$sectionId = $encryption->decrypt($sectionIdCryptogram, $decryptionIv);

if (!$sectionId){
    http_response_code(400);
    echo json_encode(new GetResponse("failure", ["errorCode" => 400, "errorMessage" => "Número de sección inválido"]));
    return;
}
// Conseguir las notas del archivo
$grades = CSVHandler::readCSV($csvFile["tmp_name"], ["Numero de cuenta", "Puntaje", "OBS"]);
if ($grades["status"] === "failure"){
    http_response_code(400);
    echo json_encode(new GetResponse("failure", ["errorCode" => 400, "errorMessage" => "El archivo no cumple con el formato"]));
    return;
}

$uploadedGrades = TeacherService::uploadGrades($sectionId, $userId, $grades["data"]);
echo json_encode($uploadedGrades);