<?php

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/resources/services/Resources.php';


  header("Content-Type: application/json");

  Request::isWrongRequestMethod('GET');

  $center = $_GET["center"];
  $primaryMajor = $_GET["primary-major"];

  if ($center === '' || $primaryMajor === '') {
    http_response_code(400);
    return json_encode(new GetResponse('failure', []));
  }

  $query = "CALL SP_GET_MAJORS_BY_LEVEL_AND_REGIONAL_CENTER(?, ?)";
  $parameterType = 'ii';
  $parameters = [(int) $primaryMajor, (int) $center];

  $response = Resources::getRequiredResources($query, $parameterType, $parameters);

  http_response_code(200);
  echo json_encode($response);


