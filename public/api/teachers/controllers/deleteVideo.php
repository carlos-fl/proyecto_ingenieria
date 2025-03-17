<?php

  include_once __DIR__ . '/../../../../services/teachers/services/Teachers.php';
  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../utils/functions/jsonParse.php';
  include_once __DIR__ . '/../../../../services/teachers/types/DataResponse.php';
  include_once __DIR__ . '/../../../../utils/functions/setErrorResponse.php';
  include_once __DIR__ . '/../../../../utils/functions/setUnauthorizedResponse.php';

  session_start();
  header('Content-Type: application/json');

  Request::isWrongRequestMethod('DELETE');
  
  if (empty($_SESSION)) {
    setUnauthorizedResponse();
    return; 
  }

  $requestBody = getJsonData();
  $deleteVideoServiceResponse = TeacherService::deleteVideo($requestBody->sectionID, $_SESSION['DNI']);

  if ($deleteVideoServiceResponse->status == 'failure') {
    setErrorResponse($deleteVideoServiceResponse); 
    return;
  }

  http_response_code(200);
  echo json_encode($deleteVideoServiceResponse);


