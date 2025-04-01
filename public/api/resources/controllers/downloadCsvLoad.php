<?php 

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/resources/services/Resources.php';

  session_start();

  header('Content-Type: text/csv');

  if (!Request::haveRol('COORDINATOR')) {
    return;
  }

  Request::isWrongRequestMethod('GET');

  $loadID = $_GET['load'];

  if (!isset($loadID)) {
    http_response_code(400);
    echo json_encode([
      "status" => "failure",
      "data" => [],
      "error" => [
        "errorCode" => 400,
        "errorMessage" => "Account number required"
      ]
    ]);
    return;
  }


  header("Content-Type: text/csv");
  header("Content-Disposition: attachment; filename=admissionsBlueprint.csv");
  header("Expires: 0");
  Resources::downloadLoadAsCsv((int) $loadID);

