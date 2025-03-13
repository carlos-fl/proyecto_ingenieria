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
      return new GetResponse(status: 'success', data: $resourcesResult->fetch_all());
    }
  }