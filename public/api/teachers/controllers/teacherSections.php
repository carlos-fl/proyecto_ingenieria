<?php

  include_once __DIR__ . '/../../../../services/teachers/services/Teachers.php';
  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/teachers/types/DataResponse.php';
  include_once __DIR__ . '/../../../../utils/functions/setUnauthorizedResponse.php';
  include_once __DIR__ . '/../../../../utils/functions/setErrorResponse.php';

  session_start();
  header('Content-Type: application/json');

  Request::isWrongRequestMethod('GET');
  
  if (empty($_SESSION)) {
    setUnauthorizedResponse(); 
    return;
  }

  $teacherNumber = $_GET['teacher-number'];
  $sectionsByTeacherResponse = TeacherService::getCurrentSections($teacherNumber);

  if (!$sectionsByTeacherResponse) {
    http_response_code(500);
    echo json_encode(new DataResponse("failure", error: new ErrorResponse(500, "Server Error")));
    return;
  }

  if ($sectionsByTeacherResponse->status == 'failure') {
    setErrorResponse($sectionsByTeacherResponse);
    return;
  }

  http_response_code(200);
  echo json_encode($sectionsByTeacherResponse);
