<?php

  include_once __DIR__ . '/../../../config/database/Database.php';
  include_once __DIR__ . '/../../../utils/types/getResponse.php';

  class Resources {

    /**
     * @param string $query
     * query to call stored procedure according to required resource
     * @param string $parametersTypes
     * string to specify quantity and types of parameters
     * @param array $parameters
     * array with dynamic parameters according to parametersTypes
     * @return GetResponse
     * object containing status and data: {status: 'success', data: []}
     */
    public static function getRequiredResources(string $query, string $parametersTypes = '', array $parameters = []): GetResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      try {
        $resourcesResult = $db->callStoredProcedure($query, $parametersTypes, $parameters, $mysqli);
      } catch(Throwable $error) {
        return new GetResponse(status: 'failure', data: []);
      }
      
      $mysqli->close();
      return new GetResponse(status: 'success', data: $resourcesResult->fetch_all(1));
    }


    public static function downloadUploadAdmissionExamResultsFormat(): void {
      $filepath = __DIR__ . '/../../../uploads/files/admissionsBlueprint.csv';
      if (!file_exists($filepath)) {
        echo json_encode([
          "status" => "failure",
          "error" => [
            "errorCode" => 404,
            "errorMessage" => "Data Not Found",
          ]
        ]);
      }
      readfile($filepath);
    }
  }