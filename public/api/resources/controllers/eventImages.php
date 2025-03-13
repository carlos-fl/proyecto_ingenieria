<?php

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/resources/services/Resources.php';

  header("Content-Type: application/json");

  Request::isWrongRequestMethod('GET');

  $query = "CALL SP_GET_ACTIVES_NEXT_EVENTS()";
  $response = Resources::getRequiredResources($query, '');

  http_response_code(200);
  echo json_encode($response);
