<?php 

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/resources/services/Resources.php';

  session_start();

  header('Content-Type: application/pdf');

  if (!Request::haveRol('COORDINATOR') && !Request::haveRol('TEACHERS') && !Request::haveRol('ADMINISTRATOR' && !Request::haveRol('STUDENTS') && !Request::haveRol('DEPARTMENT_CHAIR'))) {
    return;
  }

  Request::isWrongRequestMethod('GET');

  $request = $_GET['path'];

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

  Resources::getPDF($request);
  
