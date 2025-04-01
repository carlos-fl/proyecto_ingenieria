<?php 

  include_once __DIR__ . '/../../../../utils/classes/Request.php';
  include_once __DIR__ . '/../../../../services/coordinator/CoordinatorService.php';

  session_start();

  header('Content-Type: application/pdf');

  if (!Request::haveRol('COORDINATOR')) {
    return;
  }

  Request::isWrongRequestMethod('GET');

  $request = $_GET['q'];

  if (!isset($request)) {
    $pdf = @file_get_contents(__DIR__ . '/../../../../../uploads/files/generico.pdf');
    http_response_code(400);
    echo $pdf;
    return;
  }

  CoordinatorService::getCancellationPDF((int) $request);
  
