<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../utils/classes/Response.php';
include_once __DIR__ . '/../../../../utils/classes/CSVHandler.php';
include_once __DIR__ . '/../../../../services/resources/services/Resources.php';

header("Content-Type: application/json");

Request::isWrongRequestMethod('POST');

$inputData = json_decode(file_get_contents('php://input'), true);

if (!isset($inputData['reviewerCode'], $inputData['csvPath'], $inputData['formatType'])) {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => '400',
        'errorMessage' => 'Missing required parameters: reviewerCode, csvPath, or formatType.'
    ]));
    return;
}

$reviewerCode = $inputData['reviewerCode'];
$csvPath = $inputData['csvPath'];
$formatType = $inputData['formatType'];

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

$csvData = CSVHandler::readCSV($csvPath, ',');

if (!$csvData) {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => '400',
        'errorMessage' => 'Failed to read CSV file or file is empty.'
    ]));
    return;
}


foreach ($csvData as $row) {
    if (!isset($row['APPLICATION_CODE'], $row['EXAM_CODE'], $row['CALIFICATION'])) {
        continue;
    }

    $applicationCode = $row['APPLICATION_CODE'];
    $examCode = $row['EXAM_CODE'];
    $calification = $row['CALIFICATION'];

    if (!is_numeric($applicationCode) || !is_numeric($examCode) || !is_numeric($calification)) {
        continue;
    }

    $query = "CALL SP_UPDATE_EXAM_SCORE(?, ?, ?, ?)";
    $parameterType = 'iiis';
    $parameters = [$applicationCode, $examCode, $calification, $reviewerCode];

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
