<?php

  session_start();

  if ($_COOKIE[session_name()]) {
    $_COOKIE = [];
    setcookie(session_name(), '', time() - 3600, '/');
    session_destroy();
  } else {
    http_response_code(400);
    echo json_encode([
      "status" => "failure",
      "error" => "No active Session"
    ]);
    return;
  }


  http_response_code(200);
  echo json_encode([
    "status" => "success"
  ]);

