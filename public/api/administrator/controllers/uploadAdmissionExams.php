<?php

  include_once __DIR__ . '/../../../../services/administrator/AdministratorService.php';
  include_once __DIR__ . '/../../../../utils/types/postResponse.php';
  include_once __DIR__ . '/../../../../utils/classes/Request.php';

  session_start();

  Request::isWrongRequestMethod('POST');
  
  if (empty($_SESSION) || !in_array('ADMINISTRATOR' ,$_SESSION['ROLES'])) {
    http_response_code(401);
    echo json_encode([
      "status" => "failure",
      "error" => [
        "errorCode" => 401,
        "errorMessage" => "Unauthorized"
      ]
    ]);
    return;
  }

  if (!isset($_FILES['file'])) {
    http_response_code(500);
    echo json_encode([
      "status" => "failure",
      "error" => [
        "errorCode" => 500,
        "errorMessage" => "Csv Expected"
      ]
    ]);
    return;
  }

  $file = $_FILES['file'];
  $AdministratorServiceResponse = AdministratorService::saveAdmissionExamResults($file);

  if ($AdministratorServiceResponse->status == 'failure') {
    http_response_code($AdministratorServiceResponse->error->errorCode);
    echo json_encode($AdministratorServiceResponse);
    return;
  }

  http_response_code(200);
  echo json_encode($AdministratorServiceResponse);
  return;





