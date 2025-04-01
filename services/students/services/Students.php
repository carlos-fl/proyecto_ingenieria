<?php

include_once __DIR__ . '/../../../config/database/Database.php';
include_once __DIR__ . '/../../../utils/types/postResponse.php';
include_once __DIR__ . '/../types/StudentResponse.php';
include_once __DIR__ . '/../types/StudentData.php';
include_once __DIR__ . '/../types/StudentRequest.php';


class StudentService
{
    public static function getStudent(int $studentId): StudentResponse
    {
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();

        $query = "CALL SP_GET_STUDENT_PERSONAL_INFO(?)";

        try {
            $student = (object) $db->callStoredProcedure($query, "i", [$studentId], $mysqli);
            if ($student->num_rows == 0) {
                return new StudentResponse("failure", error: new ErrorResponse(404, "NOT FOUND"));
            }

            $studentData = $student->fetch_assoc();
            $mysqli->close();
            return new StudentResponse("success", StudentData::setPropertiesWithArray($studentData));
        } catch (Throwable $err) {
            return new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
        }
    }

    public static function getStudentClassHistory(int $studentId): StudentResponse
    {
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();

        $query = "CALL SP_GET_STUDENT_CLASS_HISTORY(?)";

        try {
            $history = (object) $db->callStoredProcedure($query, "i", [$studentId], $mysqli);

            if ($history->num_rows == 0) {
                return new StudentResponse("failure", error: new ErrorResponse(404, "No class history found for student ID $studentId"));
            }

            $historyData = $history->fetch_all(1);

            $mysqli->close();
            return new StudentResponse("success", $historyData);
        } catch (Throwable $err) {
            return new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
        }
    }

    public static function getStudentId(int $userId): ?int
    {
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();

        $query = "CALL SP_GET_STUDENT_ID(?)";

        try {
            $result = (object) $db->callStoredProcedure($query, "i", [$userId], $mysqli);
            if ($result->num_rows == 0) {
                return null;
            }

            $studentData = $result->fetch_assoc();
            $mysqli->close();
            return (int) $studentData['ID_STUDENT'];
        } catch (Throwable $err) {
            return null;
        }
    }

    public static function getStudentAccountNumber(int $userId): ?int
    {
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();

        $query = "CALL SP_GET_STUDENT_ACCOUNT_NUMBER(?)";

        try {
            $result = (object) $db->callStoredProcedure($query, "i", [$userId], $mysqli);
            if ($result->num_rows == 0) {
                return null;
            }

            $studentData = $result->fetch_assoc();
            $mysqli->close();
            return (int) $studentData['STUDENT_ACCOUNT_NUMBER'];
        } catch (Throwable $err) {
            return null;
        }
    }

    /*public static function getStudentProfile(int $studentId): StudentResponse {
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();
        
        $query = "CALL SP_GET_STUDENT_PROFILE(?)";
        
        try {
            $profile = (object) $db->callStoredProcedure($query, "i", [$studentId], $mysqli);
            if ($profile->num_rows == 0) {
                return new StudentResponse("failure", error: new ErrorResponse(404, "Student profile not found"));
            }

            $profileData = $profile->fetch_assoc();
            $mysqli->close();

            return new StudentResponse("success", [
                "description" => $profileData['description'] ?? null,
                "photos" => json_decode($profileData['photos'], true) ?? []
            ]);

        } catch (Throwable $err) {
            return new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
        }
    }
*/

    public static function getDifferentMajors($studentId)
    {
        /**Get the majors a student is not enrolled into */
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();
        $query = "CALL SP_GET_DIFFERENT_STUDENT_MAJORS(?)";
        try {
            $majors = (object) $db->callStoredProcedure($query, "i", [$studentId], $mysqli);
            return ["status" => "success", "majors" => $majors->fetch_all(MYSQLI_ASSOC)];
        } catch (\Throwable $error) {
            return ["status" => "failure", "message" => "$error", "code" => 500];
        }
    }

    public static function newStudentRequest(int $studentId, StudentRequest $studentRequest)
    {
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();
        $content = json_encode($studentRequest->getContent());
        $requestType = $studentRequest->getRequestType();
        $query = "CALL SP_NEW_STUDENT_REQUEST(?, ?, ?)";
        try {
            $request = (object) $db->callStoredProcedure($query, "iss", [$studentId, $requestType, $content], $mysqli);
            return ["status" => "success"];
        } catch (\Throwable $error) {
            return ["status" => "failure", "message" => "$error", "code" => 500];
        }
    }

    public static function getStudentMajorChangeRequests(int $studentId): array | false
    {
        /**Get all the requests of a requestType done by the student */
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();
        $query = "CALL SP_GET_STUDENT_MAJOR_CHANGE_REQUESTS(?)";
        try {
            $requests = (object) $db->callStoredProcedure($query, "i", [$studentId], $mysqli);
            return ["status" => "success", "requests" => $requests->fetch_all(MYSQLI_ASSOC)];
        } catch (\Throwable $error) {
            return ["status" => "failure", "message" => $error, "code" => 500];
        }
    }


    public static function getStudentContacts(int $studentId): StudentResponse
    {
        $db = Database::getDatabaseInstace();
        $mysqli = $db->getConnection();

        $query = "CALL SP_GET_CONTACT_LIST(?)";

        try {
            $contacts = (object) $db->callStoredProcedure($query, "i", [$studentId], $mysqli);

            if ($contacts->num_rows == 0) {
                return new StudentResponse("failure", error: new ErrorResponse(404, "No contacts found"));
            }

            $contactsData = $contacts->fetch_all(MYSQLI_ASSOC);

            $mysqli->close();
            return new StudentResponse("success", $contactsData);
        } catch (Throwable $err) {
            return new StudentResponse("failure", error: new ErrorResponse(500, $err->getMessage()));
        }
    }
}
