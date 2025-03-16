<?php

  include_once __DIR__ . '/../../../config/database/Database.php';
  include_once __DIR__ . '/../../../utils/types/postResponse.php';
  include_once __DIR__ . '/../../../utils/classes/Regex.php';
  
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
        $teacherData = $teacher->fetch_assoc(1);
        // get teacher roles
        $query = "CALL SP_GET_ROLES_BY_USER(?)";
        $roles = $db->callStoredProcedure($query, "s", [$teacherData['DNI']], $mysqli);

        $mysqli->close();
        return new TeacherResponse("success", $teacherData, $roles->fetch_all(1));

      } catch(Throwable $error) {
        return new TeacherResponse("failure", error: new ErrorResponse(500, "SERVER ERROR"));
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

      $query = "CALL SP_GET_CURRENT_SECTIONS(?)";
      try {
        $sections = $db->callStoredProcedure($query, "s", [$DNI], $mysqli);

        if ($sections->num_rows == 0) {
          return false;
        }

        $sectionsIDs = array_column($sections->fetch_all(1), "ID_SECTION");
        return in_array($sectionID, $sectionsIDs);

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
        
      } catch(Throwable) {
        new DataResponse("failure", error: new ErrorResponse(500, "server error"));
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
        $videoAdded = $db->callStoredProcedure($query, "is", [$sectionID, $URL], $mysqli);
        $mysqli->close();
        if($videoAdded && $mysqli->affected_rows > 0) {
          return new DataResponse("success");
        }

        return new DataResponse("failure", error: new ErrorResponse(500, "Could Not Save Data"));
      } catch (Throwable) {
        return new DataResponse("failure", error: new ErrorResponse(500, "Could Not Save Data"));
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

      // TODO finish service
    }
  }