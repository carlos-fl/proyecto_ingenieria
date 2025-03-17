<?php 

  include_once __DIR__ . '/../../services/teachers/types/DataResponse.php';
  include_once __DIR__ . '/../types/postResponse.php';

  function setUnauthorizedResponse(): void {
    http_response_code(401);
    echo json_encode(new DataResponse("failure", error: new ErrorResponse(401, "Unathorized")));
    return;
  }