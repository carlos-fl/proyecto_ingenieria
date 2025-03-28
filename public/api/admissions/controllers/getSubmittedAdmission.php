<?php

  include_once __DIR__ . '/../../../../utils/classes/Request.php';  
  include_once __DIR__ . '/../../../../services/applicants/AplicantService.php';

  session_start();

  header("Content-Type: application/json");

  Request::isWrongRequestMethod("GET");

  $token = $_GET["token"];
  if (trim($token) == '') {
    http_response_code(400);
    echo json_encode([
      "status" => "failure",
      "data" => null,
      "error" => [
        "errorCode" => 400,
        "errorMessage" => "Not Token Found"
      ]
    ]);
    return;
  }

  $applicantServiceResponse = ApplicantService::getApplicantSubmittedForm($token);
  
  if ($applicantServiceResponse->status == 'failure') {
    http_response_code($applicantServiceResponse->error->errorCode);
    echo json_encode($applicantServiceResponse);
    return;
  }

  http_response_code(200);
  echo json_encode($applicantServiceResponse);
  $_SESSION['TOKEN'] = $token;
