<?php

  include_once __DIR__ . '/../../utils/classes/Regex.php';
  include_once __DIR__ . '/../../config/database/Database.php';
  include_once __DIR__ . '/types/CSVProcessStatus.php';
  include_once __DIR__ . '/../../utils/types/postResponse.php';

  class CSVControl {
    private static function isValidCSV(string $type): bool {
      return in_array($type, ALLOWED_FILE_TYPES);
    }

    /**
     * @param string $filePath
     * @param array $fields
     * fields to match the csv
     * @return bool
     */
    private static function isValidCSVFormat(string $filePath, array $fields): bool {
      try {
        $content = file($filePath);
        $columns = explode(',', trim($content[0]));
        return json_encode($columns) == json_encode($fields);
      } catch(Throwable) {
        return false;
      }
    }

    private static function processAdmissionResultsCSV(string $filePath): array {
      $format = ["dni", "tipo examen", "calificacion"];
      if (!self::isValidCSV('text/csv') || !self::isValidCSVFormat($filePath, $format)) {
        return [];
      }

      try {
        $records = file($filePath); 
        array_shift($records);

        foreach($records as $record) {
          $recordInfo = explode(',', $record, 3);
          if (!Regex::isValidIdentification($recordInfo[0]) || !Regex::isValidExamName($recordInfo[1]) || !Regex::isValidScore($recordInfo[2])) {
            return [];
          }
        }
        return $records;
      } catch(Throwable) {
        return [];
      }
    }

    public static function saveAdmissionResults(string $filePath): CSVProcessStatus {
      $records = self::processAdmissionResultsCSV($filePath);
      if (count($records) == 0) {
        return new CSVProcessStatus("failure", new ErrorResponse(400, "Empty csv found"));
      }

      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      $query_save_on_load = "CALL SP_SAVE_CSV_IN_TEMP(?)";
      $query_save_res = "CALL SP_SAVE_ADMISSION_EXAM_RESULT()";
      try {
        $db->callStoredProcedure($query_save_on_load, "s", [$filePath], mysqli: $mysqli);
      } catch (Throwable $err) {
        return new CSVProcessStatus("failure", new ErrorResponse(500, $err->getMessage() . "1"));
      }
      try {
        $db->callStoredProcedure($query_save_res,'', [], mysqli: $mysqli); 
        $mysqli->close();
        return new CSVProcessStatus("success");
      } catch(Throwable $err) {
        return new CSVProcessStatus("failure", new ErrorResponse(500, $err->getMessage() . "2"));
      }
    }
  }