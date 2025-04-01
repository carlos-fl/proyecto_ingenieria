<?php 

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/coordinator/CoordinatorService.php';

  session_start();

  header('Content-Type: application/pdf');

  if (!Request::haveRol('COORDINATOR')) {
    return;
  }

  Request::isWrongRequestMethod('GET');

  $request = $_GET['q'];

  if (!isset($request)) {
    http_response_code(400);
    echo json_encode([
      "status" => "failure",
      "data" => [],
      "error" => [
        "errorCode" => 403,
        "errorMessage" => "forbidden"
      ]
    ]);
    return;
  }

  $coordinatorServiceResponse = CoordinatorService::getRequestDescription((int) $request);
  if ($coordinatorServiceResponse->status == 'failure') {
    http_response_code($coordinatorServiceResponse->error->errorCode);
    echo json_encode($coordinatorServiceResponse);
    return;
  }

  echo json_encode($coordinatorServiceResponse);
  