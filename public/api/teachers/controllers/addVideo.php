<?php

  include_once __DIR__ . '/../../../../services/teachers/services/Teachers.php';
  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../utils/functions/jsonParse.php';
  include_once __DIR__ . '/../../../../services/teachers/types/DataResponse.php';
  include_once __DIR__ . '/../../../../utils/functions/setErrorResponse.php';
  include_once __DIR__ . '/../../../../utils/functions/setUnauthorizedResponse.php';
  include_once __DIR__ . '/../../../../services/teachers/types/AddVideoRequest.php';

  session_start();
  header('Content-Type: application/json');

  Request::isWrongRequestMethod('POST');
  
  if (empty($_SESSION)) {
    setUnauthorizedResponse(); 
    return;
  }

  $requestBody = getJsonData();
  $addVideoRequest = new AddVideoRequest($requestBody->sectionID, $requestBody->URL);
  $addVideoServiceResponse = TeacherService::addVideo($addVideoRequest, $_SESSION["DNI"]);

  if ($addVideoServiceResponse->status == 'failure') {
    setErrorResponse($addVideoServiceResponse); 
    return;
  }

  http_response_code(200);
  echo json_encode($addVideoServiceResponse);

