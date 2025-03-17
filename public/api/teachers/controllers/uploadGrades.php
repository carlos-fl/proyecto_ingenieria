<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../utils/classes/Response.php';
include_once __DIR__ . '/../../../../utils/classes/CSVHandler.php';
include_once __DIR__ . '/../../../../services/resources/services/Resources.php';

header("Content-Type: application/json");

Request::isWrongRequestMethod('POST');

$inputData = json_decode(file_get_contents('php://input'), true);

if (!isset($inputData['sectionId'], $inputData['csvPath'], $inputData['formatType'])) {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => 400,
        'errorMessage' => 'Bad request. Missing sectionId, csvPath, or formatType.'
    ]));
    return;
}

$sectionId = $inputData['sectionId'];
$csvPath = $inputData['csvPath'];
$formatType = $inputData['formatType'];

if ($formatType !== 'grades') {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => 400,
        'errorMessage' => 'Invalid format type. Expected "grades".'
    ]));
    return;
}


if (!file_exists($csvPath) || !is_readable($csvPath)) {
    http_response_code(500);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => 500,
        'errorMessage' => 'CSV file not found or unreadable: ' . $csvPath
    ]));
    return;
}

$csvData = CSVHandler::readCSV($csvPath, ',');

if (!$csvData) {
    http_response_code(500);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => 500,
        'errorMessage' => 'Failed to read CSV file.'
    ]));
    return;
}

foreach ($csvData as $row) {
    if (!isset($row['idStudent'], $row['calification']) || !is_numeric($row['calification'])) {
        continue;
    }
    $idStudent = $row['idStudent'];
    $calification = $row['calification'];

    $query = "CALL SP_UPDATE_STUDENT_GRADE(?, ?, ?)";
    $parameterType = 'iii';
    $parameters = [$sectionId, $idStudent, $calification];

    $response = Resources::getRequiredResources($query, $parameterType, $parameters);

    if (!$response) {
        http_response_code(500);
        echo json_encode(new GetResponse('failure', [
            'errorCode' => 500,
            'errorMessage' => "Error updating grade for student $idStudent"
        ]));
        return;
    }
}

http_response_code(200);
echo json_encode(new GetResponse('success', [
    'message' => 'Grades uploaded successfully.'
]));
