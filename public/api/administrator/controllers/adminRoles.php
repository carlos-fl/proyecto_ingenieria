<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../utils/classes/Response.php';
include_once __DIR__ . '/../../../../services/resources/services/Resources.php';

header("Content-Type: application/json");

Request::isWrongRequestMethod('PUT');

$inputData = json_decode(file_get_contents('php://input'), true);

if (!isset($inputData['dni'], $inputData['rolesCode']) || !is_numeric($inputData['rolesCode'])) {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => 400,
        'errorMessage' => 'Bad request. Missing dni or rolesCode.'
    ]));
    return;
}

$dni = $inputData['dni'];
$roleCode = $inputData['rolesCode']; 

$query = "CALL SP_ASSIGN_ROLES_TO_USER(?, ?)";
$parameterType = 'si';
$parameters = [$dni, $roleCode];

$response = Resources::getRequiredResources($query, $parameterType, $parameters);

if (!$response) {
    http_response_code(500);
    echo json_encode(new GetResponse('failure', [
        'errorCode' => 500,
        'errorMessage' => 'Error assigning role ' . $roleCode . ' to user with DNI ' . $dni
    ]));
    return;
}

http_response_code(200);
echo json_encode(new GetResponse('success', [
    'message' => 'Role assigned successfully.'
]));