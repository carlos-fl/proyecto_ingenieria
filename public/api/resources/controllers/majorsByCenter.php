<?php

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/resources/services/Resources.php';

  header("Content-Type: application/json");

  Request::isWrongRequestMethod('GET');

  $parameter = $_GET["center"];

  if ($parameter === '') {
    http_response_code(400);
    return json_encode(new GetResponse('failure', []));
  }

  $query = "CALL SP_GET_MAJORS_BY_REGIONAL_CENTER(?)";
  $parameterType = 'i';
  $parameters = [(int)$parameter];

  $response = Resources::getRequiredResources($query, $parameterType, $parameters);

  http_response_code(200);
  echo json_encode($response);
