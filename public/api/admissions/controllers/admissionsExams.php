<?php
    include_once __DIR__ . '/../../../../utils/classes/Request.php';
    include_once __DIR__ . '/../../../../utils/classes/Response.php';
    include_once __DIR__ . '/../../../../services/resources/services/Resources.php';


    header("Content-Type: application/json");

    Request::isWrongRequestMethod('GET');

    $query = "CALL SP_GET_ALL_EXAMS()";
    $response = Resources::getRequiredResources($query, '');

    http_response_code(200);
    echo json_encode($response);