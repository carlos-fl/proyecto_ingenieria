<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../services/students/types/StudentResponse.php';
include_once __DIR__ . '/../../../../services/students/services/Students.php';
include_once __DIR__ . '/../../../../config/env/Environment.php';
include_once __DIR__ . '/../../../../utils/classes/Encrypt.php';

session_start();

Request::isWrongRequestMethod('GET');

if (empty($_SESSION)) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

$studentId = $_GET['student-id'] ?? $_SESSION["ID_STUDENT"];
if (!$studentId) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
}

$historyResponse = StudentService::getStudentClassHistory((int) $studentId);

// Encriptar ID_SECTION y guardar IV en la sesión
Environment::read();
$env = Environment::getVariables();
$encryption = new Encryption($env["CYPHER_ALGO"], $env["CYPHER_KEY"]);

foreach ($historyResponse->data as &$record) {
    if (isset($record['section'])) {  // Asegura que 'section' existe
        $encryptedSectionIv = $encryption->encrypt((string) $record['section']);
        $record["section"] = $encryptedSectionIv["value"];
        $_SESSION[$encryptedSectionIv["value"]] = $encryptedSectionIv["iv"];
    }
}

echo json_encode($historyResponse);
