<?php

include_once __DIR__ . '/../../config/database/Database.php';
include_once __DIR__ . '/types/StudentsResponse.php';
include_once __DIR__ . '/types/LoadResponse.php';
include_once __DIR__ . '/../../utils/types/postResponse.php';
include_once __DIR__ . '/types/loadPDF.php';

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
            return ["status" => "failure", "code" => $err->errorCode, "message" => $err];
        }
    }
}