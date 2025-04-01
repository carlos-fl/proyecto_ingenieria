<?php

  include_once __DIR__ . '/../../config/database/Database.php';
  include_once __DIR__ . '/types/StudentsResponse.php';
  include_once __DIR__ . '/types/LoadResponse.php';
  include_once __DIR__ . '/../../utils/types/postResponse.php';
  include_once __DIR__ . '/types/loadPDF.php';

  class CoordinatorService {
    public static function getAllStudents(): StudentsResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_GET_ALL_STUDENTS()";
      try {
        $result = $db->callStoredProcedure($query, '', [], $mysqli);
        $mysqli->close();
        if ($result->num_rows == 0) {
          return new StudentsResponse("failure", error: new ErrorResponse(404, 'Data Not Found')); 
        }

        return new StudentsResponse("success", $result->fetch_all(1));
      } catch(Throwable $err) {
        return new StudentsResponse("failure", error: new ErrorResponse(500, 'Server Error ' . $err->getMessage())); 
      }
    }

    public static function getStudent(int $accountNumber): StudentsResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_GET_ONE_STUDENT_BY_ACCOUNT_NUMBER(?)";
      try {
        $result = $db->callStoredProcedure($query, 'i', [$accountNumber], $mysqli);
        $mysqli->close();
        if ($result->num_rows == 0) {
          return new StudentsResponse("failure", error: new ErrorResponse(404, 'Data Not Found')); 
        }
        return new StudentsResponse("success", $result->fetch_assoc());
      } catch(Throwable $err) {
        return new StudentsResponse('failure', error: new ErrorResponse(500, "Server error " . $err->getMessage()));
      }
    }

    public static function getStudentsByFilter(int $campus, int $major): StudentsResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_GET_STUDENTS_BY_FILTERS(?, ?)";
      try {
        $result = $db->callStoredProcedure($query, 'ii', [$campus, $major], $mysqli);
        $mysqli->close();
        if ($result->num_rows == 0) {
          return new StudentsResponse("failure", error: new ErrorResponse(404, 'Data Not Found')); 
        }
        return new StudentsResponse("success", $result->fetch_all(1));
      } catch(Throwable $err) {
        return new StudentsResponse('failure', error: new ErrorResponse(500, "Server error " . $err->getMessage()));
      }
    }

    public static function getStudentHistory(int $accountNumber): StudentsResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_GET_STUDENT_HISTORY_BY_ACCOUNT_NUMBER(?)";
      try {
        $result = $db->callStoredProcedure($query, 'ii', [$accountNumber], $mysqli);
        $mysqli->close();
        if ($result->num_rows == 0) {
          return new StudentsResponse("failure", error: new ErrorResponse(404, 'Data Not Found')); 
        }

        $history = $result->fetch_all(1);
        $historyGrouped = [];
        foreach($history as $item) {
          if (!isset($historyGrouped[$item["YEAR"]])) {
            $historyGrouped[$item["YEAR"]] = [];
          }
          array_push($historyGrouped[$item['YEAR']], $item);
        }

        return new StudentsResponse("success", $historyGrouped);
      } catch(Throwable) {
        return new StudentsResponse('failure', error: new ErrorResponse(500, "Server error"));
      }
    }

    public static function getAcademicLoads(int $userID): LoadResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_GET_ACADEMIC_LOAD_BY_TEACHER_USER_ID(?)";
      try {
        $result = $db->callStoredProcedure($query, 'i', [$userID], $mysqli);
        $mysqli->close();
        if ($result->num_rows == 0) {
          return new LoadResponse("failure", error: new ErrorResponse(404, 'Data Not Found')); 
        }

        return new LoadResponse("success", $result->fetch_all(1));
      } catch(Throwable) {
        return new LoadResponse('failure', error: new ErrorResponse(500, "Server error"));
      }     
    }


    public static function getRequests(string $requestType): LoadResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_GET_ACTIVE_REQUESTS_FOR_COORDINATOR(?)";
      try {
        $res = $db->callStoredProcedure($query, 's', [$requestType], $mysqli);
        $mysqli->close();
        if ($res->num_rows == 0) {
          return new LoadResponse('failure', error: new ErrorResponse(404, "Data Not Found"));
        }

        return new LoadResponse('success', $res->fetch_all(1));

      } catch(Throwable $err) {
        return new LoadResponse('failure', error: new ErrorResponse(500, "Server error" . $err->getMessage()));
      }
    }

    public static function getCancellationPDF(int $requestID): void {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_GET_CANCELLATION_URL(?)";
      try {
        $res = $db->callStoredProcedure($query, 'i', [$requestID], $mysqli);
        $mysqli->close();
        if ($res->num_rows == 0) {
          $pdf = @file_get_contents(__DIR__ . '/../../uploads/files/generico.pdf');
          echo $pdf;
        }

        $content = $res->fetch_assoc();
        $path = json_decode($content['CONTENT'], true);

        if (!file_exists($path['URL'])) {
          $pdf = @file_get_contents(__DIR__ . '/../../uploads/files/generico.pdf');
        }

        $pdf = @file_get_contents($path['URL']);

        echo $pdf;
      } catch(Throwable) {
        echo $pdf = @file_get_contents(__DIR__ . '/../../uploads/files/generico.pdf');
      }
    }

    public static function getRequestDescription(int $requestID): LoadResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_GET_CANCELLATION_URL(?)";
      try {
        $res = $db->callStoredProcedure($query, "i", [$requestID], $mysqli);
        $mysqli->close();
        if ($res->num_rows == 0) {
          return new LoadResponse("failure", error: new ErrorResponse(404, 'Not Data Found'));
        }
        return new LoadResponse("success", $res->fetch_assoc());
      } catch(Throwable $err) {
        return new LoadResponse("failure", error: new ErrorResponse(500, 'Server Error ' . $err->getMessage()));
      }
    }

    public static function acceptRequest(int $requestID, string $type): LoadResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = '';
      if ($type == 'MAJORCHANGE') {
        $query = "CALL SP_ACCEPT_MAJOR_CHANGE(?)";
      } else if ($type == 'CAMPUSTRANSFER') {
        $query = "CALL SP_ACCEPT_CAMPUS_TRANSFER(?)";
      }

      try {
        $res = $db->callStoredProcedure($query, 'i', [$requestID], $mysqli);
        $mysqli->close();
        if ($res->num_rows == 0) {
          return new LoadResponse("failure", error: new ErrorResponse(500, "Could Not Update"));
        }

        return new LoadResponse("success");

      } catch(Throwable $err) {
        return new LoadResponse("failure", error: new ErrorResponse(500, "Server Error " . $err->getMessage()));
      }
    }

    public static function rejectRequest(int $requestID): LoadResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_REJECT_REQUEST_BY_COORDINATOR(?)";
      try {
        $res = $db->callStoredProcedure($query, 'i', [$requestID], $mysqli);
        $mysqli->close();
        if ($res->num_rows == 0) {
          return new LoadResponse("failure", error: new ErrorResponse(500, "Could Not Update"));
        }

        return new LoadResponse('success');

      } catch(Throwable $err) {
        return new LoadResponse("failure", error: new ErrorResponse(500, "Server Error " . $err->getMessage()));
      }
    }
  }