<?php

  include_once __DIR__ . '/../../../../services/teachers/services/Teachers.php';
  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/teachers/types/TeacherResponse.php';
  include_once __DIR__ . '/../../../../utils/functions/setErrorResponse.php';
  include_once __DIR__ . '/../../../../utils/functions/setUnauthorizedResponse.php';
  include_once __DIR__ . '/../../../../config/env/Environment.php';
  include_once __DIR__ . '/../../../../utils/classes/Encrypt.php';



  session_start();
  header('Content-Type: application/json');

  Request::isWrongRequestMethod('GET');
  
  if (empty($_SESSION)) {
    setUnauthorizedResponse();
    return;
  }

  $userId = $_SESSION["USER_ID"];
  $sectionId = $_GET["section-id"];
  $iv = $_SESSION[$sectionId];
  Environment::read();
  $env = Environment::getVariables();
  $encryption = new Encryption($env["CYPHER_ALGO"], $env["CYPHER_KEY"]);
  $decryptedSectionId = $encryption->decrypt($sectionId, $iv);
  $sectionServiceResponse = TeacherService::getSectionInfo((int) $decryptedSectionId, (int) $userId);

  http_response_code(200);
  echo json_encode($sectionServiceResponse);


