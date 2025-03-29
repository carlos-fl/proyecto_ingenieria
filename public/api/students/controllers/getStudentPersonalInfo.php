<?php

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/students/types/StudentResponse.php';
  include_once __DIR__ . '/../../../../services/students/services/Students.php';

  session_start();
  
  Request::isWrongRequestMethod('GET');
  
  if (empty($_SESSION)) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
  }

  $studentId = $_SESSION["ID_STUDENT"] ?? null;
  if (!$studentId) {
    echo json_encode(new StudentResponse("failure", error: new ErrorResponse(401, "Unauthorized")));
    return;
  }

  $studentResponse = StudentService::getStudent((int) $studentId);
  echo json_encode($studentResponse);
