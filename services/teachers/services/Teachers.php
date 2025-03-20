<?php

  include_once __DIR__ . '/../../../config/database/Database.php';
  include_once __DIR__ . '/../../../utils/types/postResponse.php';
  include_once __DIR__ . '/../../../utils/classes/Regex.php';
  include_once __DIR__ . '/../types/TeacherResponse.php';
  include_once __DIR__ . '/../../../utils/types/postResponse.php';
  include_once __DIR__ . '/../types/SectionsResponse.php';
  include_once __DIR__ . '/../types/TeacherData.php';
  include_once __DIR__ . '/../types/SectionData.php';
  
  class TeacherService {
    public static function getTeacher(int $teacherNumber): TeacherResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      $query = "CALL SP_GET_TEACHER(?)";
      try {
        $teacher = $db->callStoredProcedure($query, "i", [$teacherNumber], $mysqli);
        if ($teacher->num_rows == 0) {
          return new TeacherResponse("failure", error: new ErrorResponse(404, "NOT FOUND"));
        }

        //TODO get image blob not path
        $teacherData = $teacher->fetch_assoc();
        // get teacher roles
        $query = "CALL SP_GET_ROLES_BY_USER(?)";
        $roles = $db->callStoredProcedure($query, "s", [$teacherData['DNI']], $mysqli);

        $mysqli->close();
        return new TeacherResponse("success", TeacherData::setPropertiesWithArray($teacherData), $roles->fetch_all(1));

      } catch(Throwable $err) {
        return new TeacherResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
      }
    }

    /**
     * check if a teacher has a section and if that section is active
     */
    private static function hasSection(int $sectionID, string $DNI): bool {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      if (!Regex::isValidDNI($DNI)) {
        return false;
      }


      $query = "CALL SP_GET_TEACHER_CURRENT_SECTIONS(?)";
      try {
        $sections = $db->callStoredProcedure($query, "s", [$DNI], $mysqli);

        if ($sections->num_rows == 0) {
          return false;
        }

        $sectionsIDs = array_column($sections->fetch_all(1), "ID_SECTION");
        return in_array($sectionID, $sectionsIDs);
        $mysqli->close();

      } catch(Throwable) {
        return false;
      }
    }

    public static function getCurrentSections(int $teacherNumber): DataResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      
      $query = "CALL SP_GET_TEACHER_CURRENT_SECTIONS_DATA(?)";
      try {
        $sections = $db->callStoredProcedure($query, "i", [$teacherNumber], $mysqli);
        if ($sections->num_rows == 0) {
          return new DataResponse("failure", error: new ErrorResponse(404, "Not Data Found"));
        }

        $sectionsData = $sections->fetch_all(1);
        $mysqli->close();
        return new DataResponse("success", $sectionsData);
        
      } catch(Throwable $err) {
        return new DataResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
      }
    }

    public static function hasVideoOnSection(int $sectionID) {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      $query = "CALL SP_GET_ACTIVE_SECTION_VIDEO(?)";
      $sectionVideos = $db->callStoredProcedure($query, "i", [$sectionID], $mysqli);

      if ($sectionVideos->num_rows > 0) {
        return true;
      }

    }    

    // TODO CHECK IF VIDEO ALREADY EXISTS
    public static function addVideo(AddVideoRequest $request, string $DNI): DataResponse {
      $sectionID = $request->getSectionID();
      if (!self::hasSection($sectionID, $DNI)) {
        return new DataResponse("failure", error: new ErrorResponse(404, "Not Data Found"));
      }

      if (self::hasVideoOnSection($sectionID)) {
        return new DataResponse("failure", error: new ErrorResponse(400, "Section Already Has a Video"));
      }

      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_ADD_VIDEO_TO_SECTION(?, ?)";
      $URL = $request->getURL();
      try {
        $db->callStoredProcedure($query, "is", [$sectionID, $URL], $mysqli);
        $mysqli->close();
        return new DataResponse("success");

      } catch (Throwable $err) {
        return new DataResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
      }
    }

    public static function deleteVideo(int $sectionID, string $DNI): DataResponse {
      if (!self::hasSection($sectionID, $DNI)) {
        return new DataResponse("failure", error: new ErrorResponse(403, "forbidden"));
      }

      if (!self::hasVideoOnSection($sectionID)) {
        return new DataResponse("failure", error: new ErrorResponse(404, "No Video Found"));
      }
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      $query = "CALL SP_DELETE_SECTION_VIDEO(?)";
      try {
        $deletedVideo = $db->callStoredProcedure($query, "i", [$sectionID], $mysqli);
        return new DataResponse("success");

        $mysqli->close();

      } catch (Throwable $err) {
        return new DataResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
      }
    }

    public static function getSectionInfo(int $sectionID, string $DNI): SectionResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection(); 

      if (!self::hasSection($sectionID, $DNI)) {
        return new SectionResponse("failure", error: new ErrorResponse(404, "helluou"));
      }

      $query = "CALL SP_GET_SECTION_INFO(?)";
      $section = $db->callStoredProcedure($query, "i", [$sectionID], $mysqli);

      if ($section->num_rows == 0) {
        return new SectionResponse("failure", error: new ErrorResponse(404, "Not Data Found"));
      }

      try {
        $query = "CALL SP_GET_STUDENTS_IN_SECTION(?)";
        $students = $db->callStoredProcedure($query, "i", [$sectionID], $mysqli);
        $students = $students->fetch_all(1);
        $section = $section->fetch_assoc();
        $sectionData = new SectionData($section['CLASS_NAME'], $section['CLASS_CODE'], $section['SECTION_CODE'], $students, json_decode($section['DAYS_OF_WEEK']), $section['ID_SECTION']);
  
        return new SectionResponse("success", $sectionData);
      } catch(Throwable $err) {
        return new SectionResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
      }
    }

    public static function getTeacherNumber(string $DNI): string {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      $query = "CALL SP_GET_TEACHER_NUMBER(?)";
      try {
        $result = $db->callStoredProcedure($query, "s", [$DNI], $mysqli);
        if ($result->num_rows == 0) {
          return "";
        }

        $result = $result->fetch_assoc();
        return $result["TEACHER_NUMBER"];
      } catch(Throwable) {
        return "";
      }
    }

    public static function getEmployeeNumber($userId){
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection(); 
      $query = "CALL SP_GET_EMPLOYEE_NUMBER(?)";
      $employeeNumber = $db->callStoredProcedure($query, "i", [(int) $userId], $mysqli);
      if ($employeeNumber->num_rows == 0){
        return "";
      }
      $result = $employeeNumber->fetch_assoc();
      return $result;
    }
  }