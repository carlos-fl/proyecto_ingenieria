<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../utils/classes/Response.php';
include_once __DIR__ . '/../../../../src/services/resources/services/Resources.php';

header("Content-Type: application/json");

Request::isWrongRequestMethod('POST');

$inputData = json_decode(file_get_contents('php://input'), true);

if (!isset($inputData['firstName'], $inputData['lastName'], $inputData['id'], $inputData['phoneNumber'], $inputData['email'], $inputData['centerCode'], $inputData['gender'])) {
    http_response_code(400);
    return json_encode(new GetResponse('failure', []));
}

$firstName = $inputData['firstName'];
$lastName = $inputData['lastName'];
$id = $inputData['id'];
$phoneNumber = $inputData['phoneNumber'];
$email = $inputData['email'];
$centerCode = $inputData['centerCode'];
$gender = $inputData['gender'];

$query = "CALL SP_ADD_EMPLOYEE(?, ?, ?, ?, ?, ?, ?)";
$parameterType = 'ssissis';
$parameters = [$firstName, $lastName, $id, $phoneNumber, $email, $centerCode, $gender];

$response = Resources::getRequiredResources($query, $parameterType, $parameters);

if (!$response) {
    http_response_code(500);
    echo json_encode($response);
}
http_response_code(200);
echo json_encode($response);