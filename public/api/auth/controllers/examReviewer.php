<?php

  session_start();

  include_once __DIR__ .'/../../../../services/auth/services/auth.service.php';
  include_once __DIR__ . '/../../../../utils/classes/Logger.php';
  include_once __DIR__ . '/../../../../utils/classes/Request.php';

  header("Content-Type: application/json");

  Request::isWrongRequestMethod('POST'); 

  Logger::loginAuth('APPLICANT_REVIEWER');
