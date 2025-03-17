<?php

  function setErrorResponse($response): void {
    http_response_code($response->error->errorCode);
    echo json_encode($response);
    return;
  }