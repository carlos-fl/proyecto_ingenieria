<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../utils/classes/Response.php';
include_once __DIR__ . '/../../../../services/resources/services/Resources.php';
include_once __DIR__ . '/../../../../services/contentManagement/ContentManagement.php';

header("Content-Type: application/json");

Request::isWrongRequestMethod('POST');

$inputData = json_decode($_POST['data'] ?? '{}', true);

if (!isset($inputData['dni'], $inputData['firstName'], $inputData['lastName'], $inputData['phoneNumber'], 
          $inputData['personalEmail'], $inputData['instEmail'], $inputData['password'], 
          $inputData['gender'], $inputData['centerCode'], $inputData['salary'], $_FILES['photo'])) {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', ['errorCode' => '400', 'errorMessage' => 'Missing required fields']));
    exit;
}

try {
    $photoPath = ContentManagement::savePhoto($_FILES['photo']);
} catch (Error $e) {
    http_response_code(400);
    echo json_encode(new GetResponse('failure', ['errorCode' => '400', 'errorMessage' => $e->getMessage()]));
    exit;
}

$dni = $inputData['dni'];
$firstName = $inputData['firstName'];
$lastName = $inputData['lastName'];
$phoneNumber = $inputData['phoneNumber'];
$personalEmail = $inputData['personalEmail'];
$instEmail = $inputData['instEmail'];
$password = $inputData['password'];
$gender = $inputData['gender'];
$centerCode = $inputData['centerCode'];
$salary = $inputData['salary'];

$query = "CALL SP_ADD_EMPLOYEE(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$parameterType = 'sssssssssid';
$parameters = [$dni, $firstName, $lastName, $phoneNumber, $personalEmail, $instEmail, $password, $gender, $photoPath, $centerCode, $salary];

$response = Resources::getRequiredResources($query, $parameterType, $parameters);

if (!$response) {
    http_response_code(500);
    echo json_encode(new GetResponse('failure', ['errorCode' => '500', 'errorMessage' => 'Internal server error']));
    exit;
}

http_response_code(200);
echo json_encode(new GetResponse('success', ['employeeNumber' => $response]));