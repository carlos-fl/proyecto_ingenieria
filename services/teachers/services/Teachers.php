<?php

  include_once __DIR__ . '/../../../config/database/Database.php';
  include_once __DIR__ . '/../../../utils/types/postResponse.php';
  include_once __DIR__ . '/../../../utils/classes/Regex.php';
  include_once __DIR__ . '/../types/TeacherResponse.php';
  include_once __DIR__ . '/../../../utils/types/postResponse.php';
  include_once __DIR__ . '/../types/SectionsResponse.php';
  include_once __DIR__ . '/../types/TeacherData.php';
  include_once __DIR__ . '/../types/DataResponse.php';
  include_once __DIR__ . '/../types/SectionData.php';
  
  class TeacherService {
    public static function getTeacher(int $teacherNumber): TeacherResponse {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      $query = "CALL SP_GET_TEACHER(?)";
      try {
        $teacher = (object) $db->callStoredProcedure($query, "i", [$teacherNumber], $mysqli);
        if ($teacher->num_rows == 0) {
          return new TeacherResponse("failure", error: new ErrorResponse(404, "NOT FOUND"));
        }

        //TODO get image blob not path
        $teacherData = $teacher->fetch_assoc();
        // get teacher roles
        $query = "CALL SP_GET_ROLES_BY_USER(?)";
        $roles = (object) $db->callStoredProcedure($query, "s", [$teacherData['DNI']], $mysqli);

        $mysqli->close();
        return new TeacherResponse("success", TeacherData::setPropertiesWithArray($teacherData), $roles->fetch_all(1));

      } catch(Throwable $err) {
        return new TeacherResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
      }
    }

    /**
     * check if a teacher has a section and if that section is active
     */
    private static function hasSection(int $sectionID, int $userId): bool {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      $query = "CALL SP_GET_CURRENT_SECTIONS(?)";
      try {
        $sections = (object) $db->callStoredProcedure($query, "s", [$userId], $mysqli);

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
          $sections = (object) $db->callStoredProcedure($query, "i", [$teacherNumber], $mysqli);
          if ($sections->num_rows == 0) {
              return new DataResponse("failure", error: new ErrorResponse(404, "Not Data Found"));
          }
  
          $sectionsData = $sections->fetch_all(1);
  
          $mysqli->close();
          return new DataResponse("success", $sectionsData);
          
      } catch (Throwable $err) {
          return new DataResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
      }
    }

    public static function hasVideoOnSection(int $sectionID) {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      $query = "CALL SP_GET_SECTION_CURRENT_VIDEO(?)";
      $sectionVideos = (object) $db->callStoredProcedure($query, "i", [$sectionID], $mysqli);

      if ($sectionVideos->num_rows > 0) {
        return true;
      }
      return false;

    }    

    // TODO CHECK IF VIDEO ALREADY EXISTS
    public static function addVideo(AddVideoRequest $request, string $userId): DataResponse {
      $sectionID = $request->getSectionID();
      $URL = $request->getURL();
      if (!self::hasSection($sectionID, $userId)) {
        return new DataResponse("failure", error: new ErrorResponse(404, "Not Data Found"));
      }

      if (self::isSameVideo($sectionID, $URL)) {
        // No se puede actualizar el video por el mismo video
        return new DataResponse("failure", error: new ErrorResponse(400, "Section Already Has that Video"));
      }

      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $query = "CALL SP_ADD_VIDEO_TO_SECTION(?, ?)";

      try {
        $result = $db->callStoredProcedure($query, "is", [$sectionID, $URL], $mysqli);
        $mysqli->close();
        return new DataResponse("success");

      } catch (Throwable $err) {
        return new DataResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
      }
    }

    public static function isSameVideo($sectionId, $videoUrl){
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      $query = "CALL SP_GET_SECTION_CURRENT_VIDEO(?)";
      $sectionVideo = $db->callStoredProcedure($query, "i", [$sectionId], $mysqli);
      
      if ($sectionVideo->num_rows > 0) {
        $sectionVideoUrl = $sectionVideo->fetch_assoc()["SECTION_VIDEO_URL"];
        return $videoUrl == $sectionVideoUrl;
      }
      return false;

    }


    public static function deleteVideo(int $sectionID, string $userId): DataResponse {
      if (!self::hasSection($sectionID, $userId)) {
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

    public static function getSectionInfo(int $sectionID, string $userId): SectionResponse | array {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection(); 

      try {
        $query = "CALL SP_GET_STUDENTS_IN_SECTION(?)";
        $students = $db->callStoredProcedure($query, "i", [$sectionID], $mysqli);
        $students = $students->fetch_all(1);
        return ["status" => "success", "students" => $students];
      } catch(Throwable $err) {
        return new SectionResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
      }
    }

    public static function getTeacherNumber(string $userId): string {
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();

      $query = "CALL SP_GET_TEACHER_NUMBER(?)";
      try {
        $result = $db->callStoredProcedure($query, "s", [$userId], $mysqli);
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

    public static function getSectionCurrentVideo(int $sectionId): array | false{
      /**Retorna el  vidoe actual de una sección. Retorna False si no hay conexión con BD*/
      try{
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();
        $query = "CALL SP_GET_SECTION_CURRENT_VIDEO(?)";
        $result = (object) $db->callStoredProcedure($query, "i", [$sectionId], $mysqli);
        $mysqli->close();
        return $result->fetch_assoc() ?? ["videoUrl" => ""];
      }catch (Throwable $error){
        return false;
      }
    }

    public static function uploadGrades(int $sectionId, int $userId, array $grades){
      if (!self::hasSection($sectionId, $userId)) {
        return new DataResponse("failure", error: new ErrorResponse(404, "Section not assigned to user"));
      }
      try{
          $notFoundStudents = array();
          self::uploadGradesDB($sectionId, $grades, $notFoundStudents);
          return new DataResponse("success", data: ["notFoundStudents" => $notFoundStudents]);
      }catch (Throwable $error){
        return new DataResponse("failure", error: new ErrorResponse(500, "Error with DB Connection. ERROR: $error"));
      }
    }

    /**
     *  @param sectionId:  Id of the section the student is part of
     *  @param grades: Student grades ["Numero de Cuenta"=>, "Puntaje"=>, "OBS"=>]
     *  @param nofFoundStudents: Student grades Array passed by reference to manage students that were not found in the section
     */
    private static function uploadGradesDB($sectionId, $grades, array &$notFoundStudents): void{
      // Upload grades to the database}
      $db = Database::getDatabaseInstace();
      $mysqli = $db->getConnection();
      $hasStudentSp = "CALL SP_SECTION_HAS_STUDENT(?, ?)";
      $uploadGradesSp = "CALL SP_UPLOAD_GRADE(?, ?, ?, ?)";
      foreach($grades as $grade){
        $accountNumber = $grade["Numero de cuenta"];
        $obs = $grade["OBS"];
        $score = $grade["Puntaje"];
        $hasStudent = (object) $db->callStoredProcedure($hasStudentSp, "ii", [$sectionId, $accountNumber], $mysqli);
        if (!$hasStudent->fetch_assoc()["RESULT"]){
          // Professor doesn't have given student in its section
          $notFoundStudents[] = $accountNumber;
          continue;
        }
        // Update the student's results
        (object) $db->callStoredProcedure($uploadGradesSp, "iiis", [$sectionId, $accountNumber, $score, $obs], $mysqli);
      }
      $mysqli->close();
    }
  }