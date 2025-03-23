<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../utils/classes/Response.php';
include_once __DIR__ . '/../../../../utils/classes/CSVHandler.php';
include_once __DIR__ . '/../../../../services/contentManagement/ContentManagement.php';
include_once __DIR__ . '/../../../../services/resources/services/Resources.php';

header("Content-Type: application/json");

Request::isWrongRequestMethod('POST');

if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => '400',
        'errorMessage' => 'Missing required file: CSV file must be uploaded.'
    ]));
    return;
}

try {
    $csvPath = ContentManagement::saveFile($_FILES['file']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => '400',
        'errorMessage' => $e->getMessage()
    ]));
    return;
}

$formatType = $_POST['formatType'] ?? null;

if ($formatType !== 'exams') {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => '400',
        'errorMessage' => 'Invalid formatType. Only "exams" is supported.'
    ]));
    return;
}

if (!file_exists($csvPath) || !is_readable($csvPath)) {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => '400',
        'errorMessage' => 'CSV file not found or unreadable.'
    ]));
    return;
}

$csvData = CSVHandler::readCSV($csvPath, ['dni', 'tipo examen', 'calificacion']);

if ($csvData['status'] === 'failure') {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => '400',
        'errorMessage' => $csvData['error']
    ]));
    return;
}

foreach ($csvData['data'] as $row) {
    if (!isset($row['dni'], $row['tipo examen'], $row['calificacion'])) {
        continue;
    }

    $applicationCode = $row['dni'];
    $examCode = $row['tipo examen'];
    $calification = $row['calificacion'];

    if (!is_numeric($applicationCode) || !is_numeric($examCode) || !is_numeric($calification)) {
        continue;
    }

    $query = "CALL SP_UPDATE_EXAM_SCORE(?, ?, ?)";
    $parameterType = 'iii';
    $parameters = [$applicationCode, $examCode, $calification];

    $response = Resources::getRequiredResources($query, $parameterType, $parameters);

    if (!$response) {
        http_response_code(500);
        echo json_encode(new GetResponse('failure', [
            'errorCode' => '500',
            'errorMessage' => "Error updating exam score for application $applicationCode"
        ]));
        return;
    }
}

http_response_code(200);
echo json_encode(new GetResponse('success', [
    'message' => 'Exam scores updated successfully.'
]));