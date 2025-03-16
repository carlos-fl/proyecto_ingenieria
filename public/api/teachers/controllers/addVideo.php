<?php

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../utils/functions/jsonParse.php';

  session_start();

  Request::isWrongRequestMethod('POST');
  
  if (empty($_SESSION)) {
    echo json_encode(new DataResponse("failure", error: new ErrorResponse(401, "Unathorized")));
    return;
  }

  $requestBody = getJsonData();
  $addVideoRequest = new AddVideoRequest($requestBody->sectionID, $requestBody->URL);
  $addVideoServiceResponse = TeacherService::addVideo($addVideoRequest, $_SESSION["DNI"]);
  echo json_encode($teacherResponse);

