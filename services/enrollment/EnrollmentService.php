<?php

  include_once __DIR__ . '/../../config/database/Database.php';
  include_once __DIR__ . '/types/ActiveResponse.php';
  include_once __DIR__ . '/types/EnrollmentResponse.php';
  include_once __DIR__ . '/types/VerifySectionResponse.php';
  include_once __DIR__ . '/../../utils/types/postResponse.php';

  class EnrollmentService {
    public static function isActive(): ActiveResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_IS_ENROLLMENT_ACTIVE()";
      try {
        $res = $db->callStoredProcedure($query, '', [], $mysqli);
        $mysqli->close();
        if ($res->num_rows == 0) {
          return new ActiveResponse('failure', false, new ErrorResponse(404, 'Data Not Found'));
        }

        $res = $res->fetch_assoc();
        if ($res['ACTIVE'] == 1) {
          return new ActiveResponse('success', true);
        }

        return new ActiveResponse('success', false);

      } catch(Throwable $err) {
        return new ActiveResponse('failure', false, new ErrorResponse(500, 'Server error ' . $err->getMessage()));
      }
    }

    public static function getDepartments(int $studentID): EnrollmentResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_GET_STUDENT_CURRENT_DEPARTMENT(?)";
      try {

        $res = $db->callStoredProcedure($query, 'i', [$studentID], $mysqli);
        $mysqli->close();
        if ($res->num_rows == 0) {
          return new EnrollmentResponse('failure', error: new ErrorResponse(404, 'Not Found'));
        }

        $res = $res->fetch_assoc();
        return new EnrollmentResponse('success', [$res]);

      } catch(Throwable $err) {
        return new EnrollmentResponse('failure', error: new ErrorResponse(500, 'Server Error ' . $err->getMessage()));
      }
    }

    public static function getClassesByDepartment(int $departmentID, int $studentID): EnrollmentResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_GET_CLASSES_BY_DEPARTMENT_ID(?, ?)";
      try {
        $res = $db->callStoredProcedure($query, 'ii', [$departmentID, $studentID], $mysqli);
        $mysqli->close();
        if ($res->num_rows == 0) {
          return new EnrollmentResponse('failure', error: new ErrorResponse(404, 'Not Data Found'));
        }

        $res = $res->fetch_all(1);
        return new EnrollmentResponse('success', $res);

      } catch(Throwable $err) {
        return new EnrollmentResponse('failure', error: new ErrorResponse(500, "Server Error " . $err->getMessage()));
      }
    }

    public static function enrolledClasses(int $studentID): EnrollmentResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_GET_ENROLLED_CLASSES_BY_STUDENT(?)";
      try {

        $res = $db->callStoredProcedure($query, 'i', [$studentID], $mysqli);
        $mysqli->close();
        $res = $res->fetch_all(1);
        return new EnrollmentResponse('success', $res);

      } catch(Throwable $err) {
        return new EnrollmentResponse('failure', error: new ErrorResponse(500, 'Server Error ' . $err->getMessage()));
      }
    }

    public static function getSectionsByClass(int $classID): EnrollmentResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_GET_CURRENT_SECTIONS_BY_CLASS(?)";
      try {
        $res = $db->callStoredProcedure($query, 'i', [$classID], $mysqli);
        $mysqli->close();
        if ($res->num_rows == 0) {
          return new EnrollmentResponse('failure', error: new ErrorResponse(404, 'Not Sections Found'));
        }

        $res = $res->fetch_all(1);
        return new EnrollmentResponse('success', $res);

      } catch(Throwable $err) {
        return new EnrollmentResponse('failure', error: new ErrorResponse(500, 'Server Error ' . $err->getMessage()));
      }
    }

    public static function verifySectionToEnrol(int $sectionID, int $studentID): VerifySectionResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_GET_SECTION_TO_ENROLL(?)";
      try {
        $sectionToEnrol = $db->callStoredProcedure($query, 'i', [$sectionID], $mysqli); 
        if ($sectionToEnrol->num_rows == 0) {
          return new VerifySectionResponse('failure', false, error: new ErrorResponse(500, 'Could Not Verify'));
        }

        //TODO: verify that section is not already enrolled
        $sectionToEnrol = $sectionToEnrol->fetch_assoc();
        $currentEnrolClasses = self::enrolledClasses($studentID);

      } catch(Throwable $err) {
        return new VerifySectionResponse('failure', false, error: new ErrorResponse(500, 'Server Error ' . $err->getMessage()));
      }
    }


    public static function enrol(int $studentID, int $sectionID): EnrollmentResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_ENROL(?, ?)";
      try {

        $res = $db->callStoredProcedure($query, 'ii', [$sectionID, $studentID], $mysqli);
        $mysqli->close();
        if ($res->num_rows == 0) {
          return new EnrollmentResponse('failure', error: new ErrorResponse(500, 'Server Error'));
        }


        return new EnrollmentResponse('success');
      } catch(Throwable $err) {
        return new EnrollmentResponse('failure', error: new ErrorResponse(500, 'Server Error ' . $err->getMessage() . " $sectionID"));
      }
    }

    public static function cancelClass(int $sectionID, int $studentID): EnrollmentResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_CANCEL_ENROLLED_CLASS(?, ?)";
      try {

        $res = $db->callStoredProcedure($query, 'ii', [$sectionID, $studentID], $mysqli);
        if ($res->num_rows == 0) {
          return new EnrollmentResponse('failure', error: new ErrorResponse(500, "Could not Update"));
        }

        $mysqli->close();
        return new EnrollmentResponse("success $sectionID, $studentID");

      } catch(Throwable $err) {
        return new EnrollmentResponse('failure', error: new ErrorResponse(500, "Server Error " . $err->getMessage()));
      }
    }
  }