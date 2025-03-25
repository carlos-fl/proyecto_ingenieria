<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../utils/classes/Response.php';
include_once __DIR__ . '/../../../../services/resources/services/Resources.php';
include_once __DIR__ . '/../../../../services/contentManagement/ContentManagement.php';
include_once __DIR__ . '/../../../../services/emailNotifications/EmailService.php';

header("Content-Type: application/json");

Request::isWrongRequestMethod('POST');

$inputData = json_decode($_POST['data'] ?? '{}', true);

if (!isset($inputData['dni'], $inputData['dniType'], $inputData['firstName'], $inputData['lastName'], 
          $inputData['phoneNumber'], $inputData['personalEmail'], $inputData['gender'], 
          $inputData['regionalCenter'], $_FILES['photo'])) {
    http_response_code(400);
    echo json_encode(new Response('failure', ['errorCode' => '400', 'errorMessage' => 'Missing required fields']));
    exit;
}

try {
    $photoPath = ContentManagement::savePhoto($_FILES['photo']);
} catch (Error $e) {
    http_response_code(400);
    echo json_encode(new Response('failure', ['errorCode' => '400', 'errorMessage' => $e->getMessage()]));
    exit;
}

$tempPassword = bin2hex(random_bytes(4));

$dni = $inputData['dni'];
$dniType = $inputData['dniType'];
$firstName = $inputData['firstName'];
$lastName = $inputData['lastName'];
$phoneNumber = $inputData['phoneNumber'];
$personalEmail = $inputData['personalEmail'];
$gender = $inputData['gender'];
$regionalCenter = $inputData['regionalCenter'];

$query = "CALL SP_CREATE_TEACHER(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$parameterType = 'sisssssssi';
$parameters = [$dni, $dniType, $firstName, $lastName, $phoneNumber, $personalEmail, $tempPassword, $gender, $photoPath, $regionalCenter];

$response = Resources::getRequiredResources($query, $parameterType, $parameters);

if (!$response) {
    http_response_code(500);
    echo json_encode(new Response('failure', ['errorCode' => '500', 'errorMessage' => 'Internal server error']));
    exit;
}

$institutionalEmail = $response['generatedEmail'];

$emailTemplatePath = "teacherAccount.html";
$emailSent = EmailService::sendEmail(
    $personalEmail,
    "Bienvenido(a) - Utiliza tus credenciales de Acceso",
    [
        "name" => $firstName . " " . $lastName,
        "institutionalEmail" => $institutionalEmail,
        "password" => $tempPassword
    ],
    $emailTemplatePath
);

if (!$emailSent) {
    http_response_code(500);
    echo json_encode(new Response('failure', ['errorCode' => '500', 'errorMessage' => 'Error sending email']));
    exit;
}

http_response_code(200);
echo json_encode(new Response('success', [
    'teacherId' => $response['teacherId'],
    'institutionalEmail' => $institutionalEmail,
    'temporaryPassword' => $tempPassword
]));