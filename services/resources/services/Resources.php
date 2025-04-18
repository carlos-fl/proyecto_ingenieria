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

    private static function download(string $path) {
      readfile($path);
    }

    public static function downloadLoadAsCsv(int $id) {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = 'CALL SP_GET_ACADEMIC_LOAD_PATH(?)';
      try {
        $res = $db->callStoredProcedure($query, 'i', [$id], $mysqli);
        http_response_code(404);
        if ($res->num_rows == 0) {
          echo json_encode([
          "status" => "failure",
          "error" => [
            "errorCode" => 404,
            "errorMessage" => "Data Not Found",
          ]
        ]); 
        return;
        }
        $data = $res->fetch_assoc();
        http_response_code(200);
        self::download($data["PATH"]);
      } catch(Throwable) {

      }
    }

    public static function getPDF(string $path): void {
      if (!file_exists($path)) {
        $pdf = file_get_contents(__DIR__ . '/../../../uploads/files/generico.pdf');
        echo $pdf;
        return;
      }
      $pdf = file_get_contents($path);
      echo $pdf;
    }
  }