<?php

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/teachers/types/DataResponse.php';
  include_once __DIR__ . '/../../../../services/teachers/services/Teachers.php';
  include_once __DIR__ . '/../../../../../config/env/Environment.php';
  include_once __DIR__ . '/../../../../utils/classes/Encrypt.php';


  session_start();

  Request::isWrongRequestMethod('GET');
  
  if (empty($_SESSION)) {
    echo json_encode(new DataResponse("failure", error: new ErrorResponse(401, "Unathorized, NSN")));
    return;
  }

  $teacherNumber = _GET["teacher-number"] ?? $_SESSION["TEACHER_NUMBER"];
  if (!$teacherNumber){
    echo json_encode(new DataResponse("failure", error: new ErrorResponse(401, "Unathorized, NTN")));
    return;
  }
  $teacherResponse = TeacherService::getCurrentSections((int) $teacherNumber);

  // Encriptar los sectionId y guardar su IV en la sesión
  Environment::read();
  $env = Environment::getVariables();
  $encryption = new Encryption($env["CYPHER_ALGO"], $env["CYPHER_KEY"]);
  foreach ($teacherResponse->data as &$section) {
    $encryptedSectionIv =  $encryption->encrypt((string) $section['ID_SECTION']);
    $section["ID_SECTION"] = $encryptedSectionIv["value"];
    $_SESSION[$encryptedSectionIv["value"]] = $encryptedSectionIv["iv"];
  }
  echo json_encode($teacherResponse);
