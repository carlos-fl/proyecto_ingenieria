<?php

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../utils/functions/jsonParse.php';
  include_once __DIR__ . '/../../../../services/teachers/types/DataResponse.php';
  include_once __DIR__ . '/../../../../services/teachers/types/AddVideoRequest.php';
  include_once __DIR__ . '/../../../../config/env/Environment.php';
  include_once __DIR__ . '/../../../../utils/classes/Encrypt.php';
  include_once __DIR__ . '/../../../../services/teachers/services/Teachers.php';


  session_start();

  Request::isWrongRequestMethod('POST');
  
  if (empty($_SESSION || !in_array("TEACHER", $_SESSION['ROLES']))) {
    echo json_encode(new DataResponse("failure", error: new ErrorResponse(401, "Unathorized")));
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
    echo json_encode(new DataResponse("failure", error: new ErrorResponse(400, "Not valid Section ID")));
    return;
  }

  $addVideoRequest = new AddVideoRequest((int)$decryptedSectionId, $requestBody->URL);
  $addVideoServiceResponse = TeacherService::addVideo($addVideoRequest, $_SESSION["USER_ID"]);
  
  echo json_encode($addVideoServiceResponse);