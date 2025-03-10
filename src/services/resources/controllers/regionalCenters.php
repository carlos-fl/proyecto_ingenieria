<?php

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../services/Resources.php';

  header("Content-Type: application/json");

  Request::isWrongRequestMethod('GET');

  $query = "CALL SP_GET_REGIONAL_CENTERS()";
  $response = Resources::getRequiredResources($query, '');

  http_response_code(200);
  echo json_encode($response);

