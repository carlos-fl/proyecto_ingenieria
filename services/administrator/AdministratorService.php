<?php

  include_once __DIR__ . '/../../utils/types/postResponse.php';
  include_once __DIR__ . '/../../utils/classes/Response.php';
  include_once __DIR__ . '/../../services/contentManagement/CSVControl.php';
  include_once __DIR__ . '/../../services/contentManagement/ContentManagement.php';


  class AdministratorService {
    /**
     * @param array $file
     * associative array with csv content
     */
    public static function saveAdmissionExamResults(array $file): PostResponse {
      $path = $file["tmp_name"];
      $serviceResponse = CSVControl::saveAdmissionResults($path);
      if ($serviceResponse->status == 'success') {
        ContentManagement::saveFile($file);
        return Response::returnPostResponse(false, "success");
      }
      return Response::returnPostResponse(true, "failure", 500, $serviceResponse->error->errorMessage);
    }
  }