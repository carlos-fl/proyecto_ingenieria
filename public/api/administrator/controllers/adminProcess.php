<?php

include_once __DIR__ . '/../../../../utils/classes/Request.php';
include_once __DIR__ . '/../../../../utils/classes/Response.php';
include_once __DIR__ . '/../../../../services/resources/services/Resources.php';

header("Content-Type: application/json");

Request::isWrongRequestMethod('POST');

$inputData = json_decode(file_get_contents('php://input'), true);

if (!isset($inputData['proccessTypeCode'], $inputData['PACCode'], $inputData['startDate'], $inputData['endDate'])) {
    http_response_code(400);
    return json_encode(new GetResponse('failure', []));
}

$proccessTypeCode = $inputData['proccessTypeCode'];
$PACCode = $inputData['PACCode'];
$startDate = $inputData['startDate'];
$endDate = $inputData['endDate'];

$query = "CALL SP_CREATE_PROCESS(?, ?, ?, ?)";
$parameterType = 'iiss';
$parameters = [$proccessTypeCode, $PACCode, $startDate, $endDate];

$response = Resources::getRequiredResources($query, $parameterType, $parameters);

if (!$response) {
    http_response_code(500);
    echo json_encode($response);
}
http_response_code(200);
echo json_encode($response);