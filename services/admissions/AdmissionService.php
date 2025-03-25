<?php

  include_once __DIR__ . '/../../config/database/Database.php';

  class AdmissionService {
    public static function getReviewerWithLeastApplicationsAssign(): int {
      // get all reviewers
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = 'CALL SP_GET_REVIEWER_WITH_LEAST_APPLICATIONS()';
      try {
        $res = $db->callStoredProcedure($query, "", [], $mysqli);
        if ($res->num_rows == 0) {
          return 0;
        }
        $reviewer = $res->fetch_assoc();
        $mysqli->close();
        return $reviewer["USER_ID"];
      } catch(Throwable) {
        // do something
      }
    }
  }