<?php
include_once __DIR__ . '/../../config/database/Database.php';
include_once __DIR__ . '/types/StudentsResponse.php';

class DepartmentChairService {
    public static function getStudentRecordHeader(int $accountNumber){
        // Get the header of a student's record (Account Number, Name and Global GPA)
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();
        $query = "CALL SP_GET_ONE_STUDENT_BY_ACCOUNT_NUMBER(?)";
        try {
            $result = (object) $db->callStoredProcedure($query, 'i', [$accountNumber], $mysqli);
            $mysqli->close();
        if ($result->num_rows == 0) {
            return ["status" => "failure", "code" => 404, "message" => "No se encontró el estudiante"];
        }
            return ["status" => "success", "students" => $result->fetch_all(MYSQLI_ASSOC)];
        } catch(Throwable $err) {
            return ["status" => "failure", "code" => 500, "message" => $err->getMessage()];
        }
    }

    public static function getMajors(int $chairmanTeacherNumber){
        // Get the majors of a department
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();
        $query = "CALL SP_GET_DEPARTMENT_MAJORS(?)";
        try {
            $results = (object) $db->callStoredProcedure($query, 'i', [$chairmanTeacherNumber], $mysqli);
            $result = [];
            while ($row = $results->fetch_assoc()){
                $result[] = $row;
            }
            $mysqli->close();
            return ["status" => "success", "majors" => $result];
        } catch(Throwable $err) {
            return ["status" => "failure", "code" => 500, "message" => $err->getMessage()];
        }
    }

    public static function getAcademicLoad(int $majorId, int $chairmanTeacherNumber, int $page){
        // Retornar la carga académica de dicho Major
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();
        if (!DepartmentChairService::departmentHasMajor($majorId, $chairmanTeacherNumber, $mysqli)){
            return ["status" => "failure", "code" => 401, "message" => "Department doesn't have that major"];
        }
        $query = "CALL SP_GET_MAJOR_ACADEMIC_LOAD(?, ?)";
        try {
            $results = (object) $db->callStoredProcedure($query, 'ii', [$majorId, $page], $mysqli);
            $result = [];
            while ($row = $results->fetch_assoc()){
                $result[] = $row;
            }
            $mysqli->close();
            return ["status" => "success", "load" => $result];
        } catch(Throwable $err) {
            return ["status" => "failure", "code" => 500, "message" => $err->getMessage()];
        }
    }

    private static function departmentHasMajor($majorId, $chairmanTeacherNumber, $mysqli){
        // TODO: Finish implementation
        return true;
    }

    public static function getAcademicLoadAll($majorId, $chairmanTeacherNumber){
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();
        if (!DepartmentChairService::departmentHasMajor($majorId, $chairmanTeacherNumber, $mysqli)){
            return ["status" => "failure", "code" => 401, "message" => "Department doesn't have that major"];
        }
        $query = "CALL SP_GET_MAJOR_ACADEMIC_LOAD_ALL(?)";
        try {
            $result = (object) $db->callStoredProcedure($query, 'i', [$majorId], $mysqli);
            $mysqli->close();
            return ["status" => "success", "load" => $result->fetch_all(MYSQLI_ASSOC)];
        } catch(Throwable $err) {
            return ["status" => "failure", "code" => 500, "message" => $err->getMessage()];
        }
    }

    public static function getStudentClassHistory(int $accountNumber){
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();
        $query = "CALL SP_GET_STUDENT_HISTORY_BY_ACCOUNT_NUMBER(?)";
        try {
            $result = (object) $db->callStoredProcedure($query, 'i', [$accountNumber], $mysqli);
            $mysqli->close();
            if ($result->num_rows == 0) {
            return new StudentsResponse("failure", error: new ErrorResponse(404, 'Data Not Found')); 
            }

            $history = $result->fetch_all(1);
            $historyGrouped = [];
            foreach($history as $item) {
            if (!isset($historyGrouped[$item["PERIOD"]])) {
                $historyGrouped[$item["PERIOD"]] = [];
            }
            array_push($historyGrouped[$item['PERIOD']], $item);
            }

            return new StudentsResponse("success", $historyGrouped);
        } catch(Throwable $err) {
            return new StudentsResponse('failure', error: new ErrorResponse(500, "Server error " . $err->getMessage()));
        }
    }

    public static function getMajorClasses($majorId){
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();
        $query = "CALL SP_GET_MAJOR_CLASSES(?)";
        try {
            $results = (object) $db->callStoredProcedure($query, 'i', [$majorId], $mysqli);
            $result = [];
            while ($row = $results->fetch_assoc()){
                $result[] = $row;
            }
            $mysqli->close();
            return ["status" => "success", "classes" => $result];
        } catch(Throwable $err) {
            return ["status" => "failure", "code" => 500, "message" => $err->getMessage()];
        }
    }
}
