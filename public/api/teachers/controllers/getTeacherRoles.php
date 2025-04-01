<?php

session_start();

if (!isset($_SESSION["ROLES"])){
    http_response_code(400);
    echo json_encode(["status" == "failure"]);
    return;
}

echo json_encode(["roles" => $_SESSION["ROLES"]]);
