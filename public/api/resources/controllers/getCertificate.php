<?php 

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/resources/services/Resources.php';

  session_start();

  $request = $_GET['path'];
  $contentType = Resources::getContentType($request);

  header('Content-Type: ' . $contentType);

  if (!Request::haveRol('REVIEWER')) {
    Resources::getPDF('NOFILE');
  }

  Request::isWrongRequestMethod('GET');


  if (!isset($request)) {
    Resources::getPDF('NOFILE');
    return;
  }

  Resources::getPDF($request);
 