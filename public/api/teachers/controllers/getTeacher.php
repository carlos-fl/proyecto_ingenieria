<?php

  include_once __DIR__ . '/../../../../utils/classes/Request.php';

  session_start();

  Request::isWrongRequestMethod('GET');
  
  if (empty($_SESSION)) {
    echo json_encode(new TeacherResponse("failure", error: new ErrorResponse(401, "Unathorized")));
    return;
  }

  $teacherNumber = $_GET['teacher-number'];
  $teacherResponse = TeacherService::getTeacher((int) $teacherNumber);
  echo json_encode($teacherResponse);