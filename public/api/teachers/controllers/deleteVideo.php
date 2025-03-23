<?php

  include_once __DIR__ . '/../../../../services/teachers/services/Teachers.php';
  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../utils/functions/jsonParse.php';
  include_once __DIR__ . '/../../../../services/teachers/types/DataResponse.php';
  include_once __DIR__ . '/../../../../utils/functions/setErrorResponse.php';
  include_once __DIR__ . '/../../../../utils/functions/setUnauthorizedResponse.php';
  include_once __DIR__ . '/../../../../config/env/Environment.php';
  include_once __DIR__ . '/../../../../utils/classes/Encrypt.php';

  session_start();
  header('Content-Type: application/json');

  Request::isWrongRequestMethod('DELETE');
  
  if (empty($_SESSION)) {
    setUnauthorizedResponse();
    return; 
  }

  $requestBody = getJsonData();
  $encryptedSectionId = $requestBody->sectionId;

  if (!isset($_SESSION[$encryptedSectionId])) {
    echo json_encode(new DataResponse("failure", error: new ErrorResponse(400, "Invalid Section ID")));
    return;
  }
  $iv = $_SESSION[$encryptedSectionId];

  Environment::read();
  $env = Environment::getVariables();
  $encryption = new Encryption($env["CYPHER_ALGO"], $env["CYPHER_KEY"]);
  $decryptedSectionId = $encryption->decrypt($encryptedSectionId, $iv);

  if (!$decryptedSectionId) {
    echo json_encode(new DataResponse("failure", error: new ErrorResponse(400, "Decryption Failed")));
    return;
  }

  $deleteVideoServiceResponse = TeacherService::deleteVideo((int)$decryptedSectionId, $_SESSION['USER_ID']);

  if ($deleteVideoServiceResponse->status == 'failure') {
    setErrorResponse($deleteVideoServiceResponse);
    return;
  }

  http_response_code(200);
  echo json_encode($deleteVideoServiceResponse);
