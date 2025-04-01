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

echo json_encode($historyResponse);