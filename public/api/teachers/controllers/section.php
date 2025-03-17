<?php

  include_once __DIR__ . '/../../../../services/teachers/services/Teachers.php';
  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/teachers/types/TeacherResponse.php';
  include_once __DIR__ . '/../../../../utils/functions/setErrorResponse.php';
  include_once __DIR__ . '/../../../../utils/functions/setUnauthorizedResponse.php';

  session_start();
  header('Content-Type: application/json');

  Request::isWrongRequestMethod('GET');
  
  if (empty($_SESSION)) {
    setUnauthorizedResponse();
    return;
  }

  $DNI = $_SESSION["DNI"];
  $sectionID = $_GET["section-id"];
  $sectionServiceResponse = TeacherService::getSectionInfo($sectionID, $DNI);

  if ($sectionServiceResponse->status == 'failure') {
    setErrorResponse($sectionServiceResponse); 
    return;
  }

  http_response_code(200);
  echo json_encode($sectionServiceResponse);

