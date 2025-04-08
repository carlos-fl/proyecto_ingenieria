<?php

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../config/database/Database.php';
  include_once __DIR__ . '/../../../../services/students/types/StudentResponse.php';
  include_once __DIR__ . '/../../../../services/enrollment/EnrollmentService.php';

  session_start();

  Request::isWrongRequestMethod('POST');

  if (empty($_SESSION) || !isset($_SESSION['ID_STUDENT'])) {
      http_response_code(401);
      echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
      return;
  }

  $input = json_decode(file_get_contents('php://input'), true);
  $enrollmentServiceResponse = EnrollmentService::cancelClass((int) $input['section'], (int) $_SESSION['ID_STUDENT']);

  if ($enrollmentServiceResponse->status == 'failure') {
    http_response_code($enrollmentServiceResponse->error->errorCode);
    echo json_encode($enrollmentServiceResponse);
    return;
  }

  http_response_code(200);
  echo json_encode($enrollmentServiceResponse);


