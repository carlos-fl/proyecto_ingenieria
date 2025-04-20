<?php 

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/coordinator/CoordinatorService.php';
  include_once __DIR__ . '/../../../../utils/functions/jsonParse.php';

  session_start();

  header('Content-Type: application/json');

  if (!Request::haveRol('COORDINATOR')) {
    return;
  }

  Request::isWrongRequestMethod('POST');

  $request = getJsonData();

  if (!isset($request)) {
    http_response_code(400);
    echo json_encode([
      "status" => "failure",
      "data" => [],
      "error" => [
        "errorCode" => 400,
        "errorMessage" => "Missing data"
      ]
    ]);
    return;
  }

  $coordinatorServiceResponse = CoordinatorService::setRequestAsApproved((int) $request->requestID);
  if ($coordinatorServiceResponse->status == 'failure') {
    http_response_code($coordinatorServiceResponse->error->errorCode);
    echo json_encode($coordinatorServiceResponse);
    return;
  }

  http_response_code(200);
  echo json_encode($coordinatorServiceResponse);
 