<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../utils/classes/Response.php';
include_once __DIR__ . '/../../../../utils/classes/CSVHandler.php';
include_once __DIR__ . '/../../../../utils/classes/ContentManagement.php';
include_once __DIR__ . '/../../../../services/resources/services/Resources.php';
include_once __DIR__ . '/../../../../services/emailNotifications/EmailService.php';

header("Content-Type: application/json");

Request::isWrongRequestMethod('POST');

if (!isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => '400',
        'errorMessage' => 'Missing CSV file.'
    ]));
    return;
}

$csvPath = ContentManagement::saveFile($_FILES['file']);
$csvData = CSVHandler::readCSV($csvPath, ['APPLICATION_CODE', 'EXAM_CODE', 'CALIFICATION']);

if ($csvData['status'] !== 'success') {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => '400',
        'errorMessage' => 'Error reading the CSV file.'
    ]));
    return;
}

$processed = 0;
$errors = [];

foreach ($csvData['data'] as $row) {
    if (!isset($row['APPLICATION_CODE'], $row['EXAM_CODE'], $row['CALIFICATION'])) {
        continue;
    }

    $applicationCode = $row['APPLICATION_CODE'];
    $examCode = $row['EXAM_CODE'];
    $calification = $row['CALIFICATION'];

    if (!is_numeric($applicationCode) || !is_numeric($examCode) || !is_numeric($calification)) {
        continue;
    }

    $query = "CALL SP_UPDATE_EXAM_SCORE(?, ?, ?)";
    $parameterType = 'iii';
    $parameters = [$applicationCode, $examCode, $calification];

    $response = Resources::getRequiredResources($query, $parameterType, $parameters);

    if (!$response) {
        $errors[] = "Error updating exam score for application $applicationCode";
        continue;
    }

    $queryEmail = "SELECT U.FIRST_NAME, U.PERSONAL_EMAIL, E.EXAM_NAME, E.MIN_SCORE
               FROM TBL_USERS U
               JOIN TBL_EXAMS_X_APPLICATIONS EXA ON U.DNI = EXA.APPLICATION_CODE
               JOIN TBL_EXAMS E ON EXA.EXAM_CODE = E.EXAM_CODE
               WHERE EXA.APPLICATION_CODE = ?";
    $emailData = Resources::getRequiredResources($queryEmail, 'i', [$applicationCode]);

    if ($emailData && count($emailData) > 0) {
        $applicant = $emailData[0];
        $name = $applicant['FIRST_NAME'];
        $email = $applicant['PERSONAL_EMAIL'];
        $examName = $applicant['EXAM_NAME'];
        $minScore = $applicant['MIN_SCORE'];
        $status = ($calification >= $minScore) ? 'APROBADO' : 'REPROBADO';

        EmailService::sendEmail(
            $email,
            "Resultados del examen - $examName",
            [
                'name' => $name,
                'exam_name' => $examName,
                'calification' => $calification,
                'status' => $status
            ],
            'exam_result.html'
        );
    }

    $processed++;
}

$responseMessage = [
    'processed' => $processed,
    'errors' => $errors
];

http_response_code(200);
echo json_encode(new GetResponse('success', $responseMessage));