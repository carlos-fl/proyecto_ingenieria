<?php

  include_once __DIR__ . '/../../../../services/resources/services/Resources.php';
  include_once __DIR__ . '/../../../../utils/classes/Request.php';

  session_start();

  Request::isWrongRequestMethod('GET');

  if (empty($_SESSION) || !in_array("ADMINISTRATOR" ,$_SESSION['ROLES'])) {
    http_response_code(403);
    echo json_encode([
      "status" => "failure",
      "error" => [
        "errorCode" => 403,
        "errorMessage" => "Forbidden"
      ],
      "session" => $_SESSION
    ]);
    return;
  }

  header("Content-Type: text/csv");
  header("Content-Disposition: attachment; filename=admissionsBlueprint.csv");
  header("Expires: 0");

  Resources::downloadUploadAdmissionExamResultsFormat();
  http_response_code(200);


