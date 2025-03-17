<?php

  session_start();
  header('Content-Type: application/json');

  include_once __DIR__ .'/../../../../services/auth/services/auth.service.php';
  include_once __DIR__ . '/../../../../utils/classes/Logger.php';
  include_once __DIR__ . '/../../../../utils/classes/Request.php';

  header("Content-Type: application/json");

  Request::isWrongRequestMethod('POST');  

  Logger::loginAuth('TEACHERS');

  echo json_encode(session_id());
  echo json_encode(session_name());
